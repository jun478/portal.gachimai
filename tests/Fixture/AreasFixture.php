<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AreasFixture
 */
class AreasFixture extends TestFixture
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
                'id' => 1,
                'ken_id' => 1,
                'city_id' => 1,
                'town_id' => 1,
                'zip' => 'Lorem ',
                'office_flg' => 1,
                'delete_flg' => 1,
                'ken_name' => 'Lorem ',
                'ken_furi' => 'Lorem ',
                'city_name' => 'Lorem ipsum dolor sit ',
                'city_furi' => 'Lorem ipsum dolor sit ',
                'town_name' => 'Lorem ipsum dolor sit amet',
                'town_furi' => 'Lorem ipsum dolor sit amet',
                'town_memo' => 'Lorem ipsum do',
                'kyoto_street' => 'Lorem ipsum dolor sit amet',
                'block_name' => 'Lorem ipsum dolor sit amet',
                'block_furi' => 'Lorem ipsum dolor sit amet',
                'memo' => 'Lorem ipsum dolor sit amet',
                'office_name' => 'Lorem ipsum dolor sit amet',
                'office_furi' => 'Lorem ipsum dolor sit amet',
                'office_address' => 'Lorem ipsum dolor sit amet',
                'new_id' => 1,
            ],
        ];
        parent::init();
    }
}
