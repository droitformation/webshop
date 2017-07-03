<?php

function months_range(){
    return ['01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
        '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'];
}

function array_pad_keys(array $array, int $number){
    $result = [];

    foreach (range(1,$number) as $key){
        $result[$key] = !array_key_exists($key,$array) ? 0 : $array[$key];
    }

    return $result;
}