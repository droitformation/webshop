<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 2019-03-06
 * Time: 13:59
 */

namespace App\Droit\Statistique\Entites;

trait Chart
{
    public function set($data,$label = null)
    {
        $color = rand(0,255).', '.rand(0,255).', '.rand(0,255);

        return [
            'label' => isset($label) ? $label : '',
            'data'  => array_map('intval', $data),
            'backgroundColor' => 'rgba('.$color.', 0.2)',
            'borderColor' => 'rgba('.$color.',1)',
            'borderWidth' => 1
        ];
    }
}