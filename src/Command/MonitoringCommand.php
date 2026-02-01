<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use App\Service\SlackService;
use App\Service\GoogleSheet;
use Cake\Core\Configure;

/**
 * Monitoring command.
 * FollowUPの保守対応のサイトが正常に動いてるか監視する
 * 
 * bin/cake monitoring
 * /var/www/workspace/portal.gachimai.info/bin/cake monitoring
 */
class MonitoringCommand extends Command {

	public $io = null;
	public $slack = null;
	public $now = null;
	public $provision = null;
	public $result = [];
	private $plugin_path;
	private $limit_count = 5;

	/**
	 * The name of this command.
	 *
	 * @var string
	 */
	protected string $name = 'monitoring';

	/**
	 * Get the default command name.
	 *
	 * @return string
	 */
	public static function defaultName(): string {
		return 'monitoring';
	}

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
		$this->slack = new SlackService();

		$this->now = date('Y-m-d H:i:s');

		//GoogleSheetチェック
		$google = new GoogleSheet();
		$this->provision = $google->getProvisionList();
		if(empty($this->provision)){
			$this->slack->err('プロビジョニングリストが取得できませんでした。');
			exit;
		}
		
		//willowsは、強制的にリダイレクトされてるのでなし。
		if(isset($this->provision['willows'])){
			unset($this->provision['willows']);
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
		$this->io = $io;
		$this->log('start monitaring', 'debug');

		$result = [];
		//まずは全体のHTTP疎通確認（バッチが動く可能性があるため）
		$this->log('--- http check start ---', 'debug'); //TODO
		foreach ($this->provision as $site_key => $info) {

			$this->log('http check: ' . $site_key, 'debug');
			//if($site_key != 'jyohoku') continue;
			$this->result[$site_key] = [];
			$this->getStatuscode($site_key, $info['url']);

			//HTMLを見て表示されているか。。。
			if ($info['convert']['is_convert']) {
				$this->result[$site_key]['convert'] = [
						'status' => true,
						'update_date' => '',
						'item_count' => 0,
						'url' => $info['url'],
						'is_estate_tags' => false,
						'description' => ''
				];
				$html = file_get_contents($info['convert']['article_url']);
				if (strpos($html, "価格") !== false) {
					$this->result[$site_key]['convert']['is_estate_tags'] = true;
				}else{
					sleep(5);//5秒まって再度取得
					$html = file_get_contents($info['convert']['article_url']);
					if (strpos($html, "価格") !== false) {
						$this->result[$site_key]['convert']['is_estate_tags'] = true;
					}else{
						$this->log(PHP_EOL . $html, 'debug');
					}
				}
			}

			//iFrame表示確認
			if (isset($info['iframe']['url'])) {
				$html = file_get_contents($info['iframe']['url']);
				if (strpos($html, "toyama.vivi-f.jp") !== false && strpos($html, "iframe") !== false) {
					$this->result[$site_key]['is_iframe_tags'] = true;
				}
			}

			sleep(2);
		}

		//物件連動のチェック
		$this->log(PHP_EOL . '--- convert check start ---', 'debug');
		sleep(5); //TODO
		foreach ($this->provision as $provision => $info) {

			$this->log('convert check: ' . $provision, 'debug');
			if (!$info['convert']['is_convert']) continue;

			$this->plugin_path = "/var/www/workspace/" . $provision . "/DocumentRoot/wp-content/plugins";
			if(!is_dir($this->plugin_path)){
				$this->plugin_path = "/var/www/workspace/" . $provision . "/wp-content/plugins";
			}

			$method = $info['check_method'];
			$this->$method($provision, $info);
		}

		$this->log('end monitaring' . PHP_EOL . PHP_EOL, 'debug');

		//結果チェック
		$this->checkResult();
		exit;
	}
	
	
	/**
	 * ステータスコードを取得する
	 *
	 * @param  string $url  対象のURLを指定
	 * @param  int $timeout  ステータスの取得を試行する時間を指定(初期値: 10)
	 * @return mixed  ステータスコードが取得された場合は文字列、失敗した場合はFALSEを返します
	 */
	public function getStatuscode($site_key, $url, $timeout = 10) {

		// ユーザーエージェントを取得
		// PHP4.1以前の場合は多分 $HTTP_SERVER_VARS['HTTP_USER_AGENT'] で取れるかな…？
		$agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36";

		// cURLセッションの初期化
		$ch = curl_init();

		// cURL転送用オプションの設定
		$options = array(CURLOPT_URL => $url, // 取得する URL
				CURLOPT_HEADER => true, // ヘッダの内容も出力
				CURLOPT_NOBODY => true, // 出力からの本文を削除
				CURLOPT_TIMEOUT => $timeout, // 接続の試行を待ち続ける秒数
				CURLOPT_RETURNTRANSFER => true, // 返り値を文字列で返す
				CURLOPT_USERAGENT => $agent, // "User-Agent: " ヘッダの内容
				CURLOPT_DNS_USE_GLOBAL_CACHE => false,
				CURLOPT_SSL_FALSESTART => true,
				CURLOPT_FOLLOWLOCATION => true,
		);
		curl_setopt_array($ch, $options);

		// cURLセッションを実行
		$result = curl_exec($ch);
		// cURLセッションを閉じる
		curl_close($ch);

		// ステータスをステータスコード部分とその他に分割して取得
		$status = explode("\n", $result);
		$this->result[$site_key]['http']['status'] = true;
		$this->result[$site_key]['http']['description'] = $status[0];
		if (isset($status[0]) && strpos($status[0], '200 OK') === false) {
			$this->result[$site_key]['http']['status'] = false;
			if (empty($status[0])) {
				$this->result[$site_key]['http']['description'] = 'Can not be displayed site die';
			}
		}
		return $status[0];
	}

	/**
	 * 物件連動の監視
	 * @param type $param
	 */
	public function checkConvertJsonData($provision, $info) {
		//ファイルある？
		$target_file = $this->plugin_path . $info['convert']['path'];
		if (!is_file($target_file)) {
			$msg = '失敗: ' . $provision . 'の物件ファイルが存在しません。(' . $info['convert']['path'] . ')';
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
			return false;
		}

		$_latst_file = $this->plugin_path . $info['convert']['file_ymd_his'];
		$file_latest_ymd_his = @file_get_contents($_latst_file);
		$this->result[$provision]['convert']['update_date'] = $file_latest_ymd_his;
//		$this->result[$provision]['convert']['file_time'] = date('Y-m-d H:i:s', filemtime($target_file));
		$limit_ymd_his = date('Y-m-d H:i:s', strtotime($info['convert']['limit_ymd_his'], strtotime($this->now)));

		if (!$file_latest_ymd_his || ($file_latest_ymd_his <= $limit_ymd_his)) {
			$msg = '警告: ' . $provision . 'の物件ファイルが更新されていない可能性があります。';
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
		}

		//中身ある？
		$item_list = [];
		$file_info = pathinfo($target_file);
		if ($file_info['extension'] == 'json') {
			$item_list = json_decode(file_get_contents($target_file), true);
		} else {
			$item_list = unserialize(file_get_contents($target_file));
		}
		$this->result[$provision]['convert']['item_count'] = count($item_list);
		if (count($item_list) < 3) {
			$msg = '警告: ' . $provision . 'の物件データが' . count($item_list) . '件です。' . PHP_EOL;
			$msg .= 'リソース: ' . $info['convert']['resource_url'];
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
			return false;
		}
		return true;
	}

	/**
	 * 駅、エリア別に複数の結果ファイルが存在する場合のチェック
	 * @param type $provision
	 * @param type $info
	 */
	public function checkMultipleResultFiles($provision, $info) {
		//ファイルある？
		$target_dir = $this->plugin_path . $info['convert']['path'];

		$file_list = glob($target_dir);

		if (count($file_list) > 0) {
			$all_items = [];
			//一番最新のファイルを取得
			$latest_time = 0;
			foreach ($file_list as $_path) {
				$time = filemtime($_path);
				if ($latest_time <= $time) {
					$latest_time = $time;
				}

				$item_list = unserialize(file_get_contents($_path));
				if (empty($item_list)) continue;

				if ($provision == 'clover_living') {
					foreach ($item_list as $key => $item) {
						$all_items[$key] = $item['plice'] . " " . $item['address'];
					}
				} else {
					foreach ($item_list as $item) {
						$_re_buildname = $item['re_buildname'] ?? "";
						$all_items[$item['id']] = $item['re_address'] . " " . $_re_buildname;
					}
				}
			}

			$_latst_file = $this->plugin_path . $info['convert']['file_ymd_his'];
			$file_latest_ymd_his = @file_get_contents($_latst_file);

			$this->result[$provision]['convert']['update_date'] = $file_latest_ymd_his;
			//$this->result[$provision]['convert']['file_time'] = date('Y-m-d H:i:s', $latest_time);
			$limit_ymd_his = date('Y-m-d H:i:s', strtotime($info['convert']['limit_ymd_his'], strtotime($this->now)));

			if (!$file_latest_ymd_his || ($file_latest_ymd_his <= $limit_ymd_his)) {
				$msg = '警告: ' . $provision . 'の物件ファイルが更新されていない可能性があります。';
				$this->result[$provision]['convert']['status'] = false;
				$this->result[$provision]['convert']['description'] = $msg;
				return false;
			}

			//中身ある？
			$this->result[$provision]['convert']['item_count'] = count($all_items);
			if (count($all_items) < 3) {
				$msg = '警告: ' . $provision . 'の物件データが' . count($all_items) . '件です。' . PHP_EOL;
				$msg .= 'リソース: ' . $info['convert']['resource_url'];
				$this->result[$provision]['convert']['status'] = false;
				$this->result[$provision]['convert']['description'] = $msg;
				return false;
			}
		} else {
			$msg = '失敗: ' . $provision . 'の物件ファイルが存在しません。(' . $info['convert']['path'] . ')';
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
			return false;
		}

		return true;
	}

	public function edokenCheck($provision, $info) {
		//ファイルある？
		$target_file = $this->plugin_path . $info['convert']['path'];

		if (!is_file($target_file)) {
			$msg = '失敗: ' . $provision . 'の物件ファイルが存在しません。(' . $info['convert']['path'] . ')';
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
			return false;
		}

		$item_list = unserialize(file_get_contents($target_file));
		$file_latest_ymd_his = date('Y-m-d H:i:s', $item_list['time']);
		$this->result[$provision]['convert']['update_date'] = $file_latest_ymd_his;
//		$this->result[$provision]['convert']['file_time'] = $file_latest_ymd_his;
		$limit_ymd_his = date('Y-m-d H:i:s', strtotime($info['convert']['limit_ymd_his'], strtotime($this->now)));

		if (!$file_latest_ymd_his || ($file_latest_ymd_his <= $limit_ymd_his)) {
			$msg = '警告: ' . $provision . 'の物件ファイルが更新されていない可能性があります。';
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
			return false;
		}

		//中身のチェック
		if (empty($item_list['bukken_list']) || count($item_list['bukken_list']) < 3) {
			$msg = '警告: ' . $provision . 'の物件データが' . count($item_list['bukken_list']) . '件です。' . PHP_EOL;
			$msg .= 'リソース: ' . $info['convert']['resource_url'];
			$this->result[$provision]['convert']['status'] = false;
			$this->result[$provision]['convert']['description'] = $msg;
			return false;
		}
		$this->result[$provision]['convert']['item_count'] = count($item_list['bukken_list']);

		return true;
	}

	/**
	 * 結果をSlackで通知
	 */
	public function checkResult() {

		
		$send_msg = "";
		foreach ($this->result as $provision => $result) {
			$info = $this->provision[$provision];
			$is_error = false;
			$msg = "";
			$msg .= "■" . $info['name'] . "の結果 (" . $info['url'] . ")" . PHP_EOL;
			$msg .= "provision_cd: " . $provision . PHP_EOL;
			
			//httpの結果
			if (isset($result['http']['status']) && $result['http']['status']) {
				$msg .= "　HTTPの結果：OK" . PHP_EOL;
			} else {
				$msg .= "　HTTPの結果：NG" . PHP_EOL;
				$is_error = true;
			}

			//iframeの結果
			if (isset($result['is_iframe_tags'])) {
				if ($result['is_iframe_tags']) {
					$msg .= "　iframeの結果：OK" . PHP_EOL;
				} else {
					$msg .= "　iframeの結果：NG" . PHP_EOL;
					$is_error = true;
				}
			}

			//コンバートの結果
			if (isset($result['convert'])) {
				if (isset($result['convert']['status']) && $result['convert']['status']) {
					$msg .= "　コンバートの結果：OK" . PHP_EOL;
				} else {
					$msg .= "　コンバートの結果：NG" . PHP_EOL;
					$is_error = true;
				}
				$msg .= "　　→件数:" . $result['convert']['item_count'] . PHP_EOL;
				$msg .= "　　→更新日:" . $result['convert']['update_date'] . PHP_EOL;

				//物件タグの結果
				$_is_estate_tags = "NG";
				if (isset($result['convert']['is_estate_tags']) && $result['convert']['is_estate_tags']) {
					$_is_estate_tags = "OK";
				}else{
					$is_error = true;
				}
				$msg .= "　　→物件タグ:" . $_is_estate_tags . PHP_EOL;

				$_description = "";
				if (!empty($result['convert']['description'])) {
					$_description = $result['convert']['description'];
				}
				$msg .= "　　→説明:" . $_description . PHP_EOL;
			}
			
			if($is_error){
				$send_msg .= $msg . PHP_EOL;
			}
		}

		if(!empty($send_msg)){
			$this->slack->info($send_msg);
		}else{
			if(date('H') == 21){
				$this->slack->info('Check OK! エラーはありません');
			}			
		}
		//$this->slack->info($msg);
	}
}
