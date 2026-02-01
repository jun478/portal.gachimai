<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MStationsFixture
 */
class MStationsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'station_cd' => 1,
                'station_g_cd' => 1,
                'station_name' => 'Lorem ipsum dolor sit amet',
                'station_name_k' => 'Lorem ipsum dolor sit amet',
                'station_name_r' => 'Lorem ipsum dolor sit amet',
                'line_cd' => 1,
                'pref_cd' => 1,
                'post' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit amet',
                'lon' => 1,
                'lat' => 1,
                'open_ymd' => '2025-05-18',
                'close_ymd' => '2025-05-18',
                'e_status' => 1,
                'e_sort' => 1,
            ],
        ];
        parent::init();
    }
}
