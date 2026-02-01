<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Area Entity
 *
 * @property int $id
 * @property int|null $ken_id
 * @property int|null $city_id
 * @property int|null $town_id
 * @property string|null $zip
 * @property bool|null $office_flg
 * @property bool|null $delete_flg
 * @property string|null $ken_name
 * @property string|null $ken_furi
 * @property string|null $city_name
 * @property string|null $city_furi
 * @property string|null $town_name
 * @property string|null $town_furi
 * @property string|null $town_memo
 * @property string|null $kyoto_street
 * @property string|null $block_name
 * @property string|null $block_furi
 * @property string|null $memo
 * @property string|null $office_name
 * @property string|null $office_furi
 * @property string|null $office_address
 * @property int|null $new_id
 */
class Area extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'ken_id' => true,
        'city_id' => true,
        'town_id' => true,
        'zip' => true,
        'office_flg' => true,
        'delete_flg' => true,
        'ken_name' => true,
        'ken_furi' => true,
        'city_name' => true,
        'city_furi' => true,
        'town_name' => true,
        'town_furi' => true,
        'town_memo' => true,
        'kyoto_street' => true,
        'block_name' => true,
        'block_furi' => true,
        'memo' => true,
        'office_name' => true,
        'office_furi' => true,
        'office_address' => true,
        'new_id' => true,
    ];
}
