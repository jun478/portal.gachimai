<?php

use Cake\Core\Configure;

//朝8,10,12,13,16,18,20,22,24
$config['provision'] = [
//		"xxxx" => [
//				"name" => "xxxx",
//				"url" => "",
//				"convert" => [
//						"is_convert" => true,
//						"path" => "/follow-up/data/xxxxx",
//						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
//						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
//						"resource_url" => "xxxxxxx"
//				],
//				"memo" => "xxxxxx"
//		],		
//

		"toukai" => [
				"name" => "東海住宅タウンガイド",
				"url" => "https://townguide.10kai.co.jp/",
				"check_method" => "checkMultipleResultFiles", // 複数ファイル
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://townguide.10kai.co.jp/leisure/park/sakura-takasakigawaminamipark-20240223/",
						"path" => "/follow-up/data/cache_toukai_estate_*.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://rims-web.com/ez/bukken_get.php"
				],
				"memo" => "結果ファイルがエリア＆駅別で複数点在しているケース"
		],
		
		"asaka_mytown" => [
				"name" => "My Town 東上線!",
				"url" => "https://machi.asaka-mytown.co.jp/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://machi.asaka-mytown.co.jp/leisure/park/shiki-hibarijidoukouen-w445-20240224/",
						"path" => "/follow-up/data/mytown.json",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://www.asaka-mytown.co.jp/own/date/bukken.csv"
				],
				"memo" => "CSVをダウンロードしている,follow-up/core/MyTownCSVDownload.phpが3時間おきに実行されるはず",
		],

		"clover_living" => [
				"name" => "西湘Lover",
				"url" => "https://seisholover.clover-living.jp/",
				"check_method" => "checkMultipleResultFiles",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://seisholover.clover-living.jp/leisure/park/oiso-takadakoen-20240125/",
						"path" => "/follow-up/data/cache_api_clover_living_estate_*.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "http://api.miraie-net.com/v1/bukkens/?api="
				],
				"memo" => "通常のAPI取得"
		],

		//toho系
		"th_espresso" => [
				"name" => "Toho Shonan エスプレッソ!",
				"url" => "https://th-espresso.lets-toho.com/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://th-espresso.lets-toho.com/leisure/nature/chigasaki-koidegawa-w384-20240201/",
						"path" => "/follow-up/data/latest.json",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://www.lets-toho.jp/latest_json"
				],
				"memo" => "通常のAPI取得"
		],
		"yokohama" => [
				"name" => "YOKOHMA LIFE",
				"url" => "https://yokohama-life.th-yokohama.com/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://yokohama-life.th-yokohama.com/leisure/view/yokohama-naka-yokohamamarintower-w095-20221208/",
						"path" => "/follow-up/data/latest.json",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://www.lets-toho.jp/latest_json"
				],
				"memo" => "通常のAPI取得"
		],
		"ota_tokyo" => [
				"name" => "城南ダイアリー",
				"url" => "https://johnan-diary.toho-tokyo.com/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://johnan-diary.toho-tokyo.com/leisure/view/shinagawa-yamatsupia-w451-20240125/",
						"path" => "/follow-up/data/cache_ota_tokyo_bottom_estate.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://www.toho-tokyo.com/api/list.json"
				],
				"memo" => "通常のAPI取得"
		],
		"kokubunji" => [
				"name" => "国分寺さんぽ",
				"url" => "https://kokubunji-sanpo.toho-house.co.jp/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://kokubunji-sanpo.toho-house.co.jp/leisure/garden/kokubunji-tonogayatoteien-w475-20240221/",
						"path" => "/follow-up/data/cache_kokubunji_bottom_estate.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://www.toho-house.co.jp/api/list.json"
				],
				"memo" => "東宝ハウスだけど、他と違う、list.jsonでごそっともらってる"
		],
		//スクレイピング系
		"edoken" => [
				"name" => "松戸にすもう",
				"url" => "https://www.matsudo.edokenhouse.com/",
				"check_method" => "edokenCheck",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://www.matsudo.edokenhouse.com/leisure/temples-shrines/matsudo-kazehayazinja-w053-20240215/",
						"path" => "/follow-up/data/cache_edoken_bukken.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-24 hour', //24時間以上更新されていないとエラーってこと
						"resource_url" => "https://www.edokenhouse.com/bukkendate.csv"
				],
				"memo" => "CSVダウンロードからのスクレイピング, 結果セットがちょと違う, エリアは「松戸市」のみで、駅は複数ある"
		],
		"soutetsu" => [
				"name" => "相鉄線にのろう",
				"url" => "https://blog.sotetsu-re.co.jp/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://blog.sotetsu-re.co.jp/leisure/walking/yokohama-ryokuentoshi-izuminomori-w473-20240318/",
						"path" => "/follow-up/data/estate_list.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-24 hour', //24時間以上更新されていないとエラーってこと
						"resource_url" => "https://sotetsu-re.co.jp/buy/res/rosen/1?t=&s=new&o=desc"
				],
				"memo" => "スクレイピング"
		],
		"chigasaki" => [
				"name" => "茅ヶ崎散歩",
				"url" => "https://chigasaki.heart21.jp/",
				"check_method" => "checkConvertJsonData",
				"convert" => [
						"is_convert" => false, //スクレイピングし直し、一旦false 2025-06-04
						"article_url" => "https://chigasaki.heart21.jp/leisure/walking/chigasaki-murotadousojin-w147-20230410/",
						"path" => "/follow-up/data/estate_list.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-24 hour', //24時間以上更新されていないとエラーってこと
						"resource_url" => ""
				],
				"memo" => "スクレイピング"
		],
		//API　アクア系
		"asahi" => [
				"name" => "やっぱり八王子",
				"url" => "https://hachioji.asthcj.jp/",
				"check_method" => "checkMultipleResultFiles",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://hachioji.asthcj.jp/leisure/park/hachioji-hirayamazousikouenn-w025-20240208/",
						"path" => "/follow-up/data/cache_asahi_estate_*.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => "https://rims-web6.com/ez/bukken_get.php?company_id=50012"
				],
				"iframe" => [
						'url' => 'https://www.asthcj.jp/'
				],				
				"memo" => "エリアは「八王子市」のみ"
		],
		"sagamihara" => [
				"name" => "WeLove相模原",
				"url" => "https://sagamihara.asthmt.jp/",
				"check_method" => "checkMultipleResultFiles",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://sagamihara.asthmt.jp/leisure/park/sagamihara-midori-nakanocommunitykouen-w295-20240313/",
						"path" => "/follow-up/data/cache_sagamihara_estate_*.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => ""
				],
				"iframe" => [
						'url' => 'https://www.asthmt.jp/contents/code/top2'
				],
				"memo" => ""
		],
		"astebn" => [
				"name" => "海老名・厚木に住もう",
				"url" => "https://sumou.astebn.jp/",
				"check_method" => "checkMultipleResultFiles",
				"convert" => [
						"is_convert" => true,
						"article_url" => "https://sumou.astebn.jp/leisure/park/ebina-ohyakinrinkouen-w467-20240313/",
						"path" => "/follow-up/data/cache_astebn_estate_*.txt",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour', //6時間以上更新されていないとエラーってこと
						"resource_url" => ""
				],
				"memo" => ""
		],
		//コンバートない奴
		"jyohoku" => [
				"name" => "城北タウンガイド",
				"url" => "https://jyohokutownguide.jyohoku-estate.com/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"kouwa" => [
				"name" => "KOUWA HOMES",
				"url" => "https://townguide.kouwa-r.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],

		"issei" => [
				"name" => "つくばで暮らそう",
				"url" => "https://tsukubalive.issei-syoji.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"beaver" => [
				"name" => "八尾で暮らそう",
				"url" => "https://yaolife.beaverhouse.net/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"willows" => [
				"name" => "武蔵小山暮らし",
				"url" => "https://musashikoyama.willows.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"pro_hamasaki" => [
				"name" => "浜崎マガジン",
				"url" => "https://www.house.jp/areainfo/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],

		"oishii" => [
				"name" => "おいしい笑顔",
				"url" => "https://blog.oishii-ouchi.com/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"sasazuka" => [
				"name" => "京王線 幡ヶ谷 笹塚 代田橋 タウンガイド",
				"url" => "https://sasazuka.hitachi-estate.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"blue" => [
				"name" => "住吉・堺に暮らそう",
				"url" => "https://blog.blue-home.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"fujisawa21" => [
				"name" => "はじめよう藤沢・辻堂ライフ",
				"url" => "https://fujisawa.century21umi.com/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"daiichi_jutaku" => [
				"name" => "私の小山時間",
				"url" => "https://oyama-time.daiichi-jutaku.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"vivi" => [
				"name" => "ビビット富山",
				"url" => "https://toyama.vivi-f.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"iframe" => [
						'url' => 'https://vivi-f.jp/'
				],
				"memo" => "xxxxxx"
		],
//		"yueg" => [
//				"name" => "和歌山日和",
//				"url" => "https://wakayamabiyori.yueg.co.jp/",
//				"convert" => [
//						"is_convert" => false,
//						"path" => "/follow-up/data/xxxxx",
//						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
//						"limit_ymd_his" => '-6 hour',
//						"resource_url" => "xxxxxxx"
//				],
//				"memo" => "xxxxxx"
//		],
		"izu_fj" => [
				"name" => "伊豆に住みたい",
				"url" => "https://sumitai.izu-fj.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"burari" => [
				"name" => "ぶらり飯",
				"url" => "https://burari.c21hariki.com/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"echigo" => [
				"name" => "新潟さんぽ",
				"url" => "https://sanpo.echigohomes.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"memo" => "xxxxxx"
		],
		"oshima" => [
				"name" => "おおしま",
				"url" => "https://oshima.r-l.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "/follow-up/data/xxxxx",
						"file_ymd_his" => '/follow-up/data/latest_estate_date_ymdhis.txt',
						"limit_ymd_his" => '-6 hour',
						"resource_url" => "xxxxxxx"
				],
				"iframe" => [
						'url' => 'https://r-l.co.jp/'
				],
				"memo" => "xxxxxx"
		],
		"astymt" => [
				"name" => "やまと",
				"url" => "https://kurasou.astymt.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "xxx",
						"file_ymd_his" => 'xxx',
						"limit_ymd_his" => 'xxxx',
						"resource_url" => "xxx"
				],
				"memo" => "xxxxxx"
		],
		"futakotamagawa" => [
				"name" => "FUTAKOTAMAGAWA LIFE",
				"url" => "https://futakotamagawa.century21-life.com",
				"convert" => [
						"is_convert" => false,
						"path" => "xxx",
						"file_ymd_his" => 'xxx',
						"limit_ymd_his" => 'xxxx',
						"resource_url" => "xxx"
				],
				"memo" => "xxxxxx"
		],
		"enjoy" => [
				"name" => "横浜の食と楽しみ",
				"url" => "https://enjoy.astyhm.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "xxx",
						"file_ymd_his" => 'xxx',
						"limit_ymd_his" => 'xxxx',
						"resource_url" => "xxx"
				],
				"memo" => "xxxxxx"
		],
		"sendailikers" => [
				"name" => "仙台宮城に住もう",
				"url" => "https://sendailikers.eidaihouse.com/",
				"convert" => [
						"is_convert" => false,
						"path" => "xxx",
						"file_ymd_his" => 'xxx',
						"limit_ymd_his" => 'xxxx',
						"resource_url" => "xxx"
				],
				"memo" => "xxxxxx"
		],
		"fuchu-sanpo" => [
				"name" => "府中さんぽ",
				"url" => "https://fuchu-sanpo.toho-house.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "xxx",
						"file_ymd_his" => 'xxx',
						"limit_ymd_his" => 'xxxx',
						"resource_url" => "xxx"
				],
				"memo" => "xxxxxx"
		],
		
		"burari_mizonokuchi" => [
				"name" => "ぶらり溝の口",
				"url" => "https://burari.toho-mizonokuchi.co.jp/",
				"convert" => [
						"is_convert" => false,
						"path" => "xxx",
						"file_ymd_his" => 'xxx',
						"limit_ymd_his" => 'xxxx',
						"resource_url" => "xxx"
				],
				"memo" => "xxxxxx"
		],

];
return $config;

