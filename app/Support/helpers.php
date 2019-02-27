<?php

function delete_in_array($string,$value){

    if(!empty($string)){
        $array = explode(',',$string);
        unset($array[array_search($value, $array)]);
        return implode(',',$array);
    }

    return '';
}

function formatPeriod($start,$end)
{
    $start = \Carbon\Carbon::parse($start)->format('d/m/Y');
    $end   = \Carbon\Carbon::parse($end)->format('d/m/Y');

    return $start.' au '.$end;
}

function span_to_name($when,$span){
    setlocale(LC_ALL, 'fr_FR.UTF-8');

    if($span == 'month'){
        return \Carbon\Carbon::parse(date('Y').'-'.$when.'-01')->formatLocalized('%B');
    }
    if($span == 'week'){
        return 'semaine '.$when;
    }

    return $when;
}

function inPeriod($start,$end)
{
    return ((date('Y-m-d') >= $start) && (date('Y-m-d') <= $end));
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

function parts_name($filename){
    $parts = explode(".", $filename);
    $ext   = array_pop($parts);

    $name = implode('',$parts);

    return [$name,$ext];
}

function setEnv($key, $value)
{
    file_put_contents(app()->environmentFilePath(), str_replace(
        $key . '=' . env($key),
        $key . '=' . $value,
        file_get_contents(app()->environmentFilePath())
    ));
}

function stat_search($data){

    switch($data['group'])
    {
        case 'week':
            $perodicite = 'semaine';
            break;
        case 'year':
            $perodicite = 'année';
            break;
        case 'day':
            $perodicite = 'jour';
            break;
        case 'month':
            $perodicite = 'mois';
            break;
        default :
            $perodicite = 'année';
            break;
    }

    if($data['sum'] == 'sum-price'){
        return 'Somme ventes par '.$perodicite;
    }

    if($data['sum'] == 'sum-product'){
        return 'Somme ventes par livres par '.$perodicite;
    }

    if($data['sum'] == 'sum-status'){
        return 'Status abos par '.$perodicite;
    }

    if($data['sum'] == 'sum-title'){
        return 'Ventes de livres par '.$perodicite;
    }
}

function fillMissing($start,$end,$data){
    $range = range($start,$end);
    $result = [];

    foreach($range as $key){
        $key = str_pad($key, 2, '0', STR_PAD_LEFT);
        if(isset($data[$key])){
            $result[$key] = $data[$key];
        }
        else{
            $result[$key] = 0;
        }
    }

    return $result;
}