<?php

// src/Command/FetchSheetCommand.php

declare(strict_types=1);

namespace App\Service;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Google_Client;
use Google_Service_Sheets;
use Cake\Core\Configure;

class GoogleSheet {

	public $provision = [];

	public function __construct() {
		$this->provision = Configure::read('provision');
	}

	public function getProvisionList(): array {
		$spreadsheetId = '1WOVYSvMqVnt6240vohlWQb48iPcnYywn4wCB_1XoV0Y'; // URLから抽出
		$range = 'プロビジョニング'; // 読み取り範囲
		// Google Client セットアップ
		$client = new Google_Client();
		$client->setApplicationName('CakePHP Sheets Reader');
		$client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
		$client->setAuthConfig(CONFIG . 'followdocks-38aa3d221e8b.json');

		$service = new Google_Service_Sheets($client);

		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$values = $response->getValues();
		if (empty($values)) return [];

		$new = [];
		$del = [];
		foreach ($values as $item) {
			$status = $item['0'] ?? "";
			$env = $item['5'] ?? "";

			if (empty($status) || empty($env)) continue;
		
			if ($status === '○' && !array_key_exists($env, $this->provision)) {
				//新規登録されてるやつ,あとでconfig/provision.phpに追加してやる
				$new[] = $item;
				$this->provision[$env] = [
						"name" => $item['1'],
						"url" => $item['2'],
						"convert" => [
								"is_convert" => false,
								"path" => "xxx",
								"file_ymd_his" => 'xxx',
								"limit_ymd_his" => 'xxxx',
								"resource_url" => "xxx"
						],
						"memo" => "xxxxxx"
				];
				continue;
			}

			
			if ($status === '-' && array_key_exists($env, $this->provision)) {
				//サービスとして運用していないので、はずす、これも後でconfig/provision.phpから削除
				$del[] = $item;
				unset($this->provision[$env]);
				continue;
			}
		}
		return $this->provision;
	}
}
