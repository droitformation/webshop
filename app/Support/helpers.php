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
