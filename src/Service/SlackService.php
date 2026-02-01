<?php

namespace App\Service;

/**
 * Slackに通知する
 * @package App\Service
 */
class SlackService {

	public $slack_url = '';
	public $emoji = null;
	public $is_debug = false; //falseだったら、Slackに通知する
	public $message = "";

	public function __construct() {

		$this->slack_url = env('LPE_SLACK_URL', false);
		//アダンの通知用SlackのURL
		//$this->slack_url = env('ADAN_SLACK_URL', false);
	}

	public function push($msg) {
		if ($this->is_debug) {
			return true;
		}
		$this->message = $msg;
		return $this->request();
	}

	/**
	 * エラー系
	 */
	public function err($msg) {
		if ($this->is_debug) {
			return true;
		}		
		$this->message = "【warning】" . date('m/d H:i:s') . PHP_EOL . $msg;
		return $this->request();
	}

	/**
	 * お知らせ系
	 */
	public function info($msg) {
		if ($this->is_debug) {
			return true;
		}		
		$this->message = "【INFO】" . date('m/d H:i:s') . PHP_EOL . $msg;
		return $this->request();
	}

	/**
	 * リクエストを実行
	 */
	protected function request() {
		try {
			$ch = curl_init();
			curl_setopt_array($ch, $this->create_options());
			$result = curl_exec($ch);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($result, 0, $header_size);
			$result = substr($result, $header_size);
			curl_close($ch);
		} catch (Exception $e) {
			return false;
		}

		return array(
				'Header' => $header,
				'Result' => $result,
		);
	}

	/**
	 * リクエスト用のオプションを設定
	 */
	protected function create_options() {
		return array(
				CURLOPT_URL => $this->slack_url,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => array(
						'payload' => json_encode(array(
								'text' => $this->message,
						)),
				),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER => true,
		);
	}

}
