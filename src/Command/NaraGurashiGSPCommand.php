<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Google\Client;
use Google\Service\Sheets;
use Cake\Core\Configure;

/**
 * NaraGurashiGSP command.
 *
 * 指定されたGoogleスプレッドシートからデータを取得し、JSONファイルとして保存します。
 */
class NaraGurashiGSPCommand extends Command
{
    /**
     * @var string スプレッドシートID
     */
    private const SPREADSHEET_ID = '1mxE5FI4nCOMBij1k_zhgH5pe9oObBqIZkQK5ndgwlkg';

    /**
     * @var string 出力先ファイルパス
     */
    private const OUTPUT_FILE = ROOT . DS . 'temp' . DS . 'cache' . DS . 'nara_estate.json';
    private const OUTPUT_FILE_PRO = "/var/www/workspace/nara_gurashi/wp-content/plugins/follow-up/data";

    /**
     * @var string 認証設定ファイル
     */
    private const AUTH_CONFIG = CONFIG . 'followdocks-38aa3d221e8b.json';

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Googleスプレッドシートから奈良の不動産データを取得し、JSONファイルに保存します。');

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Starting data retrieval from Google Spreadsheet...');

        try {
            $client = new Client();
            $client->setApplicationName('CakePHP NaraGurashi Spreadsheet Reader');
            $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
            $client->setAuthConfig(self::AUTH_CONFIG);

            $service = new Sheets($client);

            // シート全体のデータを取得（シート名が不明な場合は 'Sheet1' などのデフォルトを想定、
            // 指定がない場合は最初のシートを取得するために A:Z のような範囲指定を検討）
            // スプレッドシートの構造を確認するため、まずは A:Z 範囲で取得を試みる
            $range = 'A:Z';
            $response = $service->spreadsheets_values->get(self::SPREADSHEET_ID, $range);
            $values = $response->getValues();

            if (empty($values)) {
                $io->error('No data found in the spreadsheet.');
                return self::CODE_ERROR;
            }

            // 1行目は無視する
            array_shift($values);

            if (empty($values)) {
                $io->warning('Spreadsheet contained only the header row.');
                // 空の配列を保存するか、エラーとするかは要件次第だが、ここでは空配列を保存する
            }

            $jsonData = json_encode($values, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            if ($jsonData === false) {
                $io->error('Failed to encode data to JSON.');
                return self::CODE_ERROR;
            }

            // 保存先ディレクトリの存在確認
            if(Configure::read('CAKE_ENV') !== 'production'){
                $dir = dirname(self::OUTPUT_FILE);
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                if (file_put_contents(self::OUTPUT_FILE, $jsonData) === false) {
                    $io->error('Failed to write data to ' . self::OUTPUT_FILE);
                    return self::CODE_ERROR;
                }
            }else{
                //本番の場合
                if (file_put_contents(self::OUTPUT_FILE_PRO, $jsonData) === false) {
                    $io->error('Failed to write data to ' . self::OUTPUT_FILE_PRO);
                    return self::CODE_ERROR;
                }
            }


            $io->success('Data successfully saved to ' . self::OUTPUT_FILE);
            $io->out('Total records: ' . count($values));

        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());
            return self::CODE_ERROR;
        }

        return self::CODE_SUCCESS;
    }
}
