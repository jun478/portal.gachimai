<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\Http\Client;
use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;
use Throwable;

/**
 * Scrape real estate prices in Tama-ku and Asao-ku.
 */
class TamaAsoScrapingCommand extends Command
{
    private const LIMIT = 160;

    private const URL_TEMPLATE = 'https://www.astmkg.jp/contents/code/search_result_area'
        . '?by=area&r_place1%5B%5D=14135&r_place1%5B%5D=14137'
        . '&all_pref=1&offset={page}&sort=7&limit=' . self::LIMIT;

    private const HTML_FILE_TEMPLATE = 'tamaaso_%s_%d.html';

    private const JSON_FILE_NAME = 'tamaaso_estate.json';

    private const PRODUCTION_JSON_DIRECTORY
        = '/var/www/workspace/tamaaso/DocumentRoot/wp-content/plugins/follow-up/data/';

    private const FIELD_MAP = [
        '所在地' => 'address',
        '交通' => 'access',
        '土地' => 'land',
        '建物' => 'building',
        '間取り' => 'floor_plan',
        '築年月' => 'built_date',
        '土地面積' => 'land_area',
    ];

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return 'Scrape Tama-ku and Asao-ku real estate prices and save them as JSON.';
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        try {
            $this->ensureDirectories();

            $date = date('Ymd');
            $firstHtml = $this->getHtml(1, $date, $io);
            $totalPages = $this->extractTotalPages($firstHtml);
            $expectedCount = $this->extractExpectedCount($firstHtml);
            $estates = [];

            for ($page = 1; $page <= $totalPages; $page++) {
                $html = $page === 1 ? $firstHtml : $this->getHtml($page, $date, $io);
                $pageEstates = $this->extractEstates($html);

                if ($pageEstates === []) {
                    throw new RuntimeException(sprintf('No estates found on page %d.', $page));
                }

                array_push($estates, ...$pageEstates);
                $io->out(sprintf('Parsed page %d/%d: %d estates', $page, $totalPages, count($pageEstates)));
            }

            if ($expectedCount !== null && count($estates) !== $expectedCount) {
                throw new RuntimeException(sprintf(
                    'Estate count mismatch. Expected %d, parsed %d.',
                    $expectedCount,
                    count($estates),
                ));
            }

            $json = json_encode(
                $estates,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR,
            );
            $jsonPath = $this->jsonDirectory() . self::JSON_FILE_NAME;
            $this->writeAtomically($jsonPath, $json . PHP_EOL);
            if (!chmod($jsonPath, 0755)) {
                throw new RuntimeException('Failed to change JSON file permissions: ' . $jsonPath);
            }

            $io->success(sprintf('Saved %d estates to %s', count($estates), $jsonPath));

            return self::CODE_SUCCESS;
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return self::CODE_ERROR;
        }
    }

    /**
     * Return today's cached HTML or fetch and cache the requested page.
     */
    private function getHtml(int $page, string $date, ConsoleIo $io): string
    {
        $path = $this->htmlDirectory() . sprintf(self::HTML_FILE_TEMPLATE, $date, $page);

        if (is_file($path)) {
            $html = file_get_contents($path);
            if ($html === false || $html === '') {
                throw new RuntimeException('Failed to read cached HTML: ' . $path);
            }

            $io->out(sprintf('Using cached HTML for page %d: %s', $page, $path));

            return $html;
        }

        $url = str_replace('{page}', (string)$page, self::URL_TEMPLATE);
        $client = new Client();
        $response = $client->get($url, [], [
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml',
                'User-Agent' => 'Mozilla/5.0 (compatible; LPE TamaAsoScraper/1.0)',
            ],
            'timeout' => 30,
        ]);

        if (!$response->isOk()) {
            throw new RuntimeException(sprintf(
                'Failed to fetch page %d. HTTP status: %d',
                $page,
                $response->getStatusCode(),
            ));
        }

        $html = $response->getStringBody();
        if ($html === '') {
            throw new RuntimeException(sprintf('The response body for page %d was empty.', $page));
        }

        // Parse before caching so an invalid response is not reused for the rest of the day.
        $this->createXPath($html);
        $this->writeAtomically($path, $html);
        $io->out(sprintf('Fetched and cached page %d: %s', $page, $path));

        return $html;
    }

    /**
     * Extract estate data from each table.searchResult_koma element.
     *
     * @return list<array{
     *   id: string,
     *   title: string,
     *   price: string,
     *   url: string,
     *   image_url: ?string,
     *   address: ?string,
     *   access: ?string,
     *   land: ?string,
     *   building: ?string,
     *   floor_plan: ?string,
     *   built_date: ?string,
     *   land_area: ?string
     * }>
     */
    private function extractEstates(string $html): array
    {
        $xpath = $this->createXPath($html);
        $tables = $xpath->query(
            '//table[contains(concat(" ", normalize-space(@class), " "), " searchResult_koma ")]',
        );
        if ($tables === false) {
            throw new RuntimeException('Failed to find estate elements in the HTML.');
        }

        $estates = [];
        foreach ($tables as $index => $table) {
            $titleNodes = $xpath->query('.//li[1]', $table);
            if ($titleNodes === false || $titleNodes->length === 0) {
                throw new RuntimeException(sprintf('Title not found for estate %d.', $index + 1));
            }

            $title = preg_replace(
                '/[\s\x{00a0}\x{3000}]+/u',
                ' ',
                trim($titleNodes->item(0)?->textContent ?? ''),
            );
            if ($title === null || $title === '') {
                throw new RuntimeException(sprintf('Title was empty for estate %d.', $index + 1));
            }

            $priceNodes = $xpath->query(
                './/*[contains(concat(" ", normalize-space(@class), " "), " searchResult_priceBig ")]',
                $table,
            );
            if ($priceNodes === false || $priceNodes->length === 0) {
                throw new RuntimeException(sprintf('Price not found for estate %d.', $index + 1));
            }

            $price = preg_replace('/[\s\x{00a0}\x{3000}]+/u', '', $priceNodes->item(0)?->textContent ?? '');
            if ($price === null || $price === '') {
                throw new RuntimeException(sprintf('Price was empty for estate %d.', $index + 1));
            }

            $urlNodes = $xpath->query('.//a[@href]/@href', $table);
            if ($urlNodes === false || $urlNodes->length === 0) {
                throw new RuntimeException(sprintf('URL not found for estate %d.', $index + 1));
            }

            $url = trim($urlNodes->item(0)?->nodeValue ?? '');
            if ($url === '') {
                throw new RuntimeException(sprintf('URL was empty for estate %d.', $index + 1));
            }

            $urlinfo = parse_url($url);
            $urlexp = explode('/', $urlinfo['path']);

            $imageNodes = $xpath->query(
                './/div[contains(concat(" ", normalize-space(@class), " "), " searchResult_contents ")]'
                . '/ul[2]/li/img[1]/@src',
                $table,
            );
            $imageUrl = $imageNodes === false || $imageNodes->length === 0
                ? null
                : trim($imageNodes->item(0)?->nodeValue ?? '');
            if ($imageUrl === '') {
                $imageUrl = null;
            }

            $fields = $this->extractFields($xpath, $table);
            $estates[] = [
                'id' => $urlexp['4'],
                'title' => $title,
                'price' => $price,
                'url' => $url,
                'image_url' => $imageUrl,
                ...$fields,
            ];
        }

        return $estates;
    }

    /**
     * Extract values from list items that start with a searchResult_fontBold label.
     *
     * @return array{
     *   address: ?string,
     *   access: ?string,
     *   land: ?string,
     *   building: ?string,
     *   floor_plan: ?string,
     *   built_date: ?string,
     *   land_area: ?string
     * }
     */
    private function extractFields(DOMXPath $xpath, DOMElement $table): array
    {
        $fields = [
            'address' => null,
            'access' => null,
            'land' => null,
            'building' => null,
            'floor_plan' => null,
            'built_date' => null,
            'land_area' => null,
        ];
        $labelNodes = $xpath->query(
            './/*[contains(concat(" ", normalize-space(@class), " "), " searchResult_fontBold ")]',
            $table,
        );
        if ($labelNodes === false) {
            throw new RuntimeException('Failed to read estate fields from the HTML.');
        }

        foreach ($labelNodes as $labelNode) {
            $label = preg_replace('/[\s\x{00a0}\x{3000}]+/u', '', $labelNode->textContent);
            if ($label === null || !isset(self::FIELD_MAP[$label])) {
                continue;
            }

            $valueParts = [];
            for ($node = $labelNode->nextSibling; $node !== null; $node = $node->nextSibling) {
                if (
                    $node instanceof DOMElement
                    && preg_match('/(^|\s)searchResult_fontBold(\s|$)/', $node->getAttribute('class')) === 1
                ) {
                    break;
                }

                $valueParts[] = $node->textContent;
            }

            $value = preg_replace(
                '/[\s\x{00a0}\x{3000}]+/u',
                ' ',
                implode('', $valueParts),
            );
            if ($value !== null) {
                $value = trim($value);
                if ($label === '間取り') {
                    $value = preg_replace('/[（(]\s*$/u', '', $value);
                } elseif ($label === '築年月') {
                    $value = preg_replace('/[）)]\s*$/u', '', $value);
                }
            }

            $key = self::FIELD_MAP[$label];
            $fields[$key] = $value === null || $value === '' ? null : $value;
        }

        return $fields;
    }

    /**
     * Read the last page number from the offset selector.
     */
    private function extractTotalPages(string $html): int
    {
        $xpath = $this->createXPath($html);
        $options = $xpath->query('//select[@name="offset"]/option/@value');
        if ($options === false) {
            throw new RuntimeException('Failed to read pagination from the HTML.');
        }

        $totalPages = 1;
        foreach ($options as $option) {
            $page = filter_var($option->nodeValue, FILTER_VALIDATE_INT);
            if ($page !== false && $page > $totalPages) {
                $totalPages = $page;
            }
        }

        return $totalPages;
    }

    /**
     * Read the total number of matching estates for an integrity check.
     */
    private function extractExpectedCount(string $html): ?int
    {
        $xpath = $this->createXPath($html);
        $nodes = $xpath->query(
            '//*[contains(concat(" ", normalize-space(@class), " "), " search_result_list_count ")]',
        );
        if ($nodes === false || $nodes->length === 0) {
            return null;
        }

        $count = preg_replace('/\D+/', '', $nodes->item(0)?->textContent ?? '');

        return $count === null || $count === '' ? null : (int)$count;
    }

    /**
     * Create an XPath instance for the received HTML.
     */
    private function createXPath(string $html): DOMXPath
    {
        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML($html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        if (!$loaded) {
            throw new RuntimeException('Failed to parse the received HTML.');
        }

        return new DOMXPath($document);
    }

    /**
     * Create output directories when they do not exist.
     */
    private function ensureDirectories(): void
    {
        $directories = array_unique([
            $this->baseDirectory(),
            $this->htmlDirectory(),
            $this->jsonDirectory(),
        ]);

        foreach ($directories as $directory) {
            if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
                throw new RuntimeException('Failed to create directory: ' . $directory);
            }
        }
    }

    /**
     * Replace a file only after all data has been written successfully.
     */
    private function writeAtomically(string $path, string $contents): void
    {
        $temporaryPath = tempnam(dirname($path), basename($path) . '.');
        if ($temporaryPath === false) {
            throw new RuntimeException('Failed to create a temporary file for: ' . $path);
        }

        try {
            if (file_put_contents($temporaryPath, $contents, LOCK_EX) === false) {
                throw new RuntimeException('Failed to write file: ' . $path);
            }
            if (!rename($temporaryPath, $path)) {
                throw new RuntimeException('Failed to replace file: ' . $path);
            }
        } finally {
            if (is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
        }
    }

    /**
     * Return the base directory for scraped data.
     */
    private function baseDirectory(): string
    {
        return TMP . 'tamaaso' . DS;
    }

    /**
     * Return the directory for cached HTML.
     */
    private function htmlDirectory(): string
    {
        return $this->baseDirectory() . 'html' . DS;
    }

    /**
     * Return the JSON output directory for the current environment.
     */
    private function jsonDirectory(): string
    {
        if (Configure::read('CAKE_ENV') === 'production') {
            return self::PRODUCTION_JSON_DIRECTORY;
        }

        return $this->baseDirectory();
    }
}
