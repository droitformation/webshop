<?php

function delete_in_array($string,$value){

    if(!empty($string)){
        $array = explode(',',$string);
        unset($array[array_search($value, $array)]);
        return implode(',',$array);
    }

    return '';
}

function var_exist(array $data, $index){
    return isset($data[$index]) && !empty($data[$index]) ? true : false;
}
