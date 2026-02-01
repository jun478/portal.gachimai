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
class AreasController extends AppController {

	public $pref = [1 => '北海道', 2 => '青森県', 3 => '岩手県', 4 => '宮城県', 5 => '秋田県', 6 => '山形県', 7 => '福島県', 8 => '茨城県', 9 => '栃木県', 10 => '群馬県', 11 => '埼玉県', 12 => '千葉県', 13 => '東京都', 14 => '神奈川県', 15 => '新潟県', 16 => '富山県', 17 => '石川県', 18 => '福井県', 19 => '山梨県', 20 => '長野県', 21 => '岐阜県', 22 => '静岡県', 23 => '愛知県', 24 => '三重県', 25 => '滋賀県', 26 => '京都府', 27 => '大阪府', 28 => '兵庫県', 29 => '奈良県', 30 => '和歌山県', 31 => '鳥取県', 32 => '島根県', 33 => '岡山県', 34 => '広島県', 35 => '山口県', 36 => '徳島県', 37 => '香川県', 38 => '愛媛県', 39 => '高知県', 40 => '福岡県', 41 => '佐賀県', 42 => '長崎県', 43 => '熊本県', 44 => '大分県', 45 => '宮崎県', 46 => '鹿児島県', 47 => '沖縄県'];

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

	/**
	 * View層でJsonViewを使用することを指定
	 *
	 * @return array
	 */
	public function viewClasses(): array {
		return [JsonView::class];
	}

	public function all() {
		$all_areas = [];
		
		$areasTable = TableRegistry::getTableLocator()->get('Areas');
		foreach ($this->pref as $ken_id => $ken_name) {
			$all_areas[$ken_id]['name'] = $ken_name;
			$all_areas[$ken_id]['city_list'] = [];

			//市区町村データゲット
			$select = ['city_id', 'city_name'];
			$conditions = ['ken_id' => $ken_id, 'delete_flg' => 0,];
			$citys = $areasTable->find()->select($select)->where($conditions)->group(['city_id', 'city_name'])->all();
			foreach ($citys as $item) {
				$all_areas[$ken_id]['city_list'][$item->city_id]['name'] = $item->city_name;
				$all_areas[$ken_id]['city_list'][$item->city_id]['town_list'] = [];

				//番地情報ゲット
				$select = ['town_id', 'town_name'];
				$conditions = ['city_id' => $item->city_id, 'town_name !=' => "", 'delete_flg' => 0,];
				$towns = $areasTable->find()->select($select)->where($conditions)->all();
				foreach ($towns as $banchi) {
					$all_areas[$ken_id]['city_list'][$item->city_id]['town_list'][$banchi->town_id] = $banchi->town_name;
				}
			}
		}
		
		$this->set(compact('all_areas'));
		$this->viewBuilder()->setOption('serialize', ['all_areas']);
//		$this->set([
//				'all_areas' => $all_areas,
//				'_serialize' => ['all_areas']
//		]);
	}

	public function city() {
		$areasTable = TableRegistry::getTableLocator()->get('Areas');
		$select = ['city_id', 'city_name'];
		$conditions = [
				'ken_id' => $this->request->getQuery('ken_id'),
				'delete_flg' => 0,
		];
		$citys = $areasTable->find()->select($select)->where($conditions)->group(['city_id', 'city_name'])->all();

		$this->set(compact('citys'));
		$this->viewBuilder()->setOption('serialize', ['citys']);

//		$this->set([
//				'citys' => $citys,
//				'_serialize' => ['citys']
//		]);
	}

	public function town() {
		$areasTable = TableRegistry::getTableLocator()->get('Areas');
		$select = ['town_id', 'town_name'];
		$conditions = [
				'city_id' => $this->request->getQuery('city_id'),
				'town_name !=' => "",
				'delete_flg' => 0,
		];
		$towns = $areasTable->find()->select($select)->where($conditions)->all();

				$this->set(compact('towns'));
		$this->viewBuilder()->setOption('serialize', ['towns']);

//		$this->set([
//				'towns' => $towns,
//				'_serialize' => ['towns']
//		]);
	}
}
