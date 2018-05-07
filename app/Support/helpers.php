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

function sortArrayByArray(Array $array, Array $orderArray){
    $ordered = array();

    foreach($orderArray as $key)
    {
        if(array_key_exists($key,$array))
        {
            $ordered[$key] = $array[$key];
            unset($array[$key]);
        }
    }

    return $ordered + $array;
}

function emptyDirectory($dir) {

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!emptyDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
}

function orderBoxes($weight){

    $shippings = orderBoxesShipping($weight);

    return collect($shippings)->groupBy(function ($item, $key) {
        return ($item->value/1000).' Kg | '.$item->price_cents;
    })->map(function ($item, $key) {
        return $item->count();
    });
}

function orderBoxesShipping($weight, $paquets = []){

    $model = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');

    if($weight > 0){
        $shipping  = $model->getShipping($weight);
        $paquets[] = $shipping;
        $newweight = $weight - $shipping->value;
        $paquets   = array_merge(orderBoxesShipping($newweight, $paquets));
    }

    return $paquets;
}

function civilites($civilite_id){
    $civilites = [1 => 'Monsieur', 2 => 'Madame', 3 => '', 4 => ''];

    return isset($civilites[$civilite_id]) ? $civilites[$civilite_id] : '';
}

function isSubstitute($email){
    return substr(strrchr($email, "@"), 1) == 'publications-droit.ch' ? true : false;
}

function substituteEmail(){
    return substr(md5(openssl_random_pseudo_bytes(32)),-11).'@publications-droit.ch';
}