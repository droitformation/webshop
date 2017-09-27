<?php

function delete_in_array($string,$value){

    if(!empty($string)){
        $array = explode(',',$string);
        unset($array[array_search($value, $array)]);
        return implode(',',$array);
    }

    return '';
}

function var_exist($data, $index){
    if($data && is_array($data)){
        return isset($data[$index]) && !empty($data[$index]) ? true : false;
    }

    return false;
}

function floor_last_page($values){
    $last = array_keys($values);
    $last = array_pop($last);
    return floor($last/10) * 10;
}

function range_before_current_page($current){

    $range = [];

    $threshold_before = floor($current/10) * 10;

    if(($current - 4 < $threshold_before) && ($threshold_before - 10) >= 0){
        $threshold_before = $threshold_before - 10;
    }

    if($threshold_before >= 10){
        foreach (range(10,$threshold_before,10) as $dix){
            $range[] = $dix;
        }
    }

    return $range;
}

function range_after_current_page($current, $last){

    $range = [];

    $threshold_after = ceil($current/10) * 10;
    $threshold_last  = floor($last/10) * 10;

    if($current + 4 > $threshold_after){
        $threshold_after = $threshold_after + 10;
    }

    if($threshold_after < $threshold_last){
        foreach (range($threshold_after,$threshold_last,10) as $dix){
            if($dix < $last){
                $range[] = $dix;
            }
        }
    }

    return $range;
}