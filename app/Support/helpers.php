<?php

function delete_in_array($string,$value){

    if(!empty($string)){
        $array = explode(',',$string);
        unset($array[array_search($value, $array)]);
        return implode(',',$array);
    }

    return '';
}