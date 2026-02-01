<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MLinesFixture
 */
class MLinesFixture extends TestFixture
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
                'line_cd' => 1,
                'company_cd' => 1,
                'line_name' => 'Lorem ipsum dolor sit amet',
                'line_name_k' => 'Lorem ipsum dolor sit amet',
                'line_name_h' => 'Lorem ipsum dolor sit amet',
                'line_color_c' => 'Lorem ',
                'line_color_t' => 'Lorem ipsum dolor sit amet',
                'line_type' => 'Lorem ipsum do',
                'lon' => 1,
                'lat' => 1,
                'zoom' => 1,
                'e_status' => 1,
                'e_sort' => 1,
            ],
        ];
        parent::init();
    }
}
