<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use App\Service\GoogleSheet;
use Cake\Core\Configure;

/**
 * Monitoring command.
 * FollowUPの保守対応のサイトが正常に動いてるか監視する
 * 
 * bin/cake wp_update
 * /var/www/workspace/portal.gachimai.info/bin/cake monitoring
 */
class WpUpdateCommand extends Command {

	public $provision = null;

	/**
	 * Get the command description.
	 *
	 * @return string
	 */
	public static function getDescription(): string {
		return 'Command description here.';
	}

	/**
	 * Hook method for defining this command's option parser.
	 *
	 * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
	 * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
	 * @return \Cake\Console\ConsoleOptionParser The built parser.
	 */
	public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
		date_default_timezone_set('Asia/Tokyo');

		//GoogleSheetチェック
		$google = new GoogleSheet();
		$this->provision = $google->getProvisionList();
		if (empty($this->provision)) {
			die('プロビジョニングリストが取得できませんでした。');
		}
		return parent::buildOptionParser($parser)->setDescription(static::getDescription());
	}

	/**
	 * Implement this method with your command's logic.
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 * @return int|null|void The exit code or null for success
	 */
	public function execute(Arguments $args, ConsoleIo $io) {
		$cmd = "";

		$param = $args->getArgumentAt(0) ?? "wp";

		$count = 1;
		foreach ($this->provision as $env_name => $value) {

			$dir = "/var/www/workspace/" . $env_name . "/DocumentRoot";
			if (!is_dir($dir)) {
				$dir = "/var/www/workspace/" . $env_name;
				if (!is_dir($dir)) {
					die($env_name . ' のディレクトリがない');
				}
			}

			$cmd .= 'echo "---- ' . $env_name . ' -----"' . PHP_EOL;
			if ($param === 'wp') {
				$cmd .= 'cd ' . $dir . PHP_EOL;
				$cmd .= 'wp core update && wp core update-db && wp plugin update --all && wp theme update --all && wp core language update' . PHP_EOL;
				$cmd .= 'echo "' . $value['url'] . '"' . PHP_EOL . PHP_EOL . PHP_EOL;
			} elseif ($param === 'pull' || $param === 'st') {
				$cmd .= 'cd ' . $dir . '/wp-content/plugins/follow-up/' . PHP_EOL;

				if ($param == 'st') {
					$cmd .= 'git status' . PHP_EOL;
				} else {
					$cmd .= 'git pull origin master' . PHP_EOL;
				}

				if ($env_name != 'pro_hamasaki') {
					$cmd .= 'echo "----' . $env_name . ' themes -----";' . PHP_EOL;

					$cmd .= 'cd ' . $dir . '/wp-content/themes/jstork19_custom/' . PHP_EOL;

					if ($param == 'st') {
						$cmd .= 'git status' . PHP_EOL;
					} else {
						$cmd .= 'git pull origin master' . PHP_EOL;
					}
				}

				$cmd .= 'echo "";' . PHP_EOL . PHP_EOL . PHP_EOL;
			}

			$count++;
		}

		echo "total provision: " . $count . PHP_EOL . PHP_EOL;
		echo $cmd;
	}
}
