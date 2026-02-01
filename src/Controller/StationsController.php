<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\ORM\TableRegistry; // TableRegistry を使用するために必要
use Cake\View\JsonView; // JSONレスポンスのために必要

/**
 * Areas Controller
 *
 * @property \App\Model\Table\AreasTable $Areas
 *
 * @method \App\Model\Entity\Area[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StationsController extends AppController {

	public $pref = [1 => '北海道', 2 => '青森県', 3 => '岩手県', 4 => '宮城県', 5 => '秋田県', 6 => '山形県', 7 => '福島県', 8 => '茨城県', 9 => '栃木県', 10 => '群馬県', 11 => '埼玉県', 12 => '千葉県', 13 => '東京都', 14 => '神奈川県', 15 => '新潟県', 16 => '富山県', 17 => '石川県', 18 => '福井県', 19 => '山梨県', 20 => '長野県', 21 => '岐阜県', 22 => '静岡県', 23 => '愛知県', 24 => '三重県', 25 => '滋賀県', 26 => '京都府', 27 => '大阪府', 28 => '兵庫県', 29 => '奈良県', 30 => '和歌山県', 31 => '鳥取県', 32 => '島根県', 33 => '岡山県', 34 => '広島県', 35 => '山口県', 36 => '徳島県', 37 => '香川県', 38 => '愛媛県', 39 => '高知県', 40 => '福岡県', 41 => '佐賀県', 42 => '長崎県', 43 => '熊本県', 44 => '大分県', 45 => '宮崎県', 46 => '鹿児島県', 47 => '沖縄県'];

	/**
	 * View層でJsonViewを使用することを指定
	 *
	 * @return array
	 */
	public function viewClasses(): array {
		return [JsonView::class];
	}
	
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function index() {
		$areas = $this->paginate($this->Areas);
		$this->set([
				'areas' => $areas,
				'_serialize' => ['areas']
		]);
	}

	public function all() {
		$this->MLines = TableRegistry::getTableLocator()->get('MLines');
		$this->MStations = TableRegistry::getTableLocator()->get('MStations');
		
		$line_info = [];
		$all = $this->MLines->find()->select(['line_cd', 'line_name'])->where(['e_status !=' => 2])->all();
		foreach ($all as $value) {
			$all_st = $this->MStations->find()->select(['station_cd', 'station_g_cd', 'station_name'])->where(['line_cd' => $value->line_cd, 'e_status !=' => 2])->all();
			if ($all_st->isEmpty()) continue;

			$line_info[$value->line_cd]['name'] = $value->line_name;
			$line_info[$value->line_cd]['station_list'] = [];

			foreach ($all_st as $item) {
				$line_info[$value->line_cd]['station_list'][$item->station_cd] = $item->station_name;
			}
		}
		$this->set(compact('line_info'));
		$this->viewBuilder()->setOption('serialize', ['line_info']);

		
//		$this->set([
//				'line_info' => $line_info,
//				'_serialize' => ['line_info']
//		]);
	}

	public function all_station() {
		
	}
}
