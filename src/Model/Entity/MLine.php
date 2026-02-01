<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MLine Entity
 *
 * @property int $line_cd
 * @property int $company_cd
 * @property string $line_name
 * @property string|null $line_name_k
 * @property string|null $line_name_h
 * @property string|null $line_color_c
 * @property string|null $line_color_t
 * @property string|null $line_type
 * @property float|null $lon
 * @property float|null $lat
 * @property int|null $zoom
 * @property int|null $e_status
 * @property int|null $e_sort
 */
class MLine extends Entity
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
        'company_cd' => true,
        'line_name' => true,
        'line_name_k' => true,
        'line_name_h' => true,
        'line_color_c' => true,
        'line_color_t' => true,
        'line_type' => true,
        'lon' => true,
        'lat' => true,
        'zoom' => true,
        'e_status' => true,
        'e_sort' => true,
    ];
}
