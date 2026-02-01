<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MStation Entity
 *
 * @property int $station_cd
 * @property int $station_g_cd
 * @property string $station_name
 * @property string|null $station_name_k
 * @property string|null $station_name_r
 * @property int $line_cd
 * @property int|null $pref_cd
 * @property string|null $post
 * @property string|null $address
 * @property float|null $lon
 * @property float|null $lat
 * @property \Cake\I18n\Date|null $open_ymd
 * @property \Cake\I18n\Date|null $close_ymd
 * @property int|null $e_status
 * @property int|null $e_sort
 */
class MStation extends Entity
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
        'station_g_cd' => true,
        'station_name' => true,
        'station_name_k' => true,
        'station_name_r' => true,
        'line_cd' => true,
        'pref_cd' => true,
        'post' => true,
        'address' => true,
        'lon' => true,
        'lat' => true,
        'open_ymd' => true,
        'close_ymd' => true,
        'e_status' => true,
        'e_sort' => true,
    ];
}
