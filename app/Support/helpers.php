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

function removeTag($str) {
    return strip_tags($str,'<p><b><strong><ul><li><i><h1><h2><h3><h4><h5>');
}

function frontendDate($date){
    setlocale(LC_ALL, 'fr_FR.UTF-8');
    $thedate = \Carbon\Carbon::createFromFormat('Y-m-d',$date);
    return $thedate->formatLocalized('%A %d %B %Y');
}

function traverse($pages, $prefix = '-'){
    foreach ($pages as $page) {
        echo PHP_EOL.$prefix.' '.$page->title;
        traverse($page->children, $page.'-');
    }
}

function validateListEmail($results){

    return collect($results)->map(function ($values) {
        return collect($values)->map(function ($items) {
            return collect($items)->reject(function ($value, $key) {
                return !filter_var($value, FILTER_VALIDATE_EMAIL) || empty($value);
            })->unique()->all();
        })->all();
    });
}


/**
 * Remove any elements where the callback returns true
 *
 * @param  array    $array    the array to walk
 * @param  callable $callback callback takes ($value, $key, $userdata)
 * @param  mixed    $userdata additional data passed to the callback.
 * @return array
 */
function array_walk_recursive_delete(array &$array, callable $callback, $userdata = null)
{
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            $value = array_walk_recursive_delete($value, $callback, $userdata);
        }
        if ($callback($value, $key, $userdata)) {
            unset($array[$key]);
        }
    }

    return $array;
}

function makeDate($request){
    $date = $request->input('date',date('Y-m-d'));

    return \Carbon\Carbon::parse($date)->toDateTimeString();
}

function couponProductOrder($order,$product){

    if(isset($order->coupon)){
        if($order->coupon->products->contains($product->id)){
            return $order->coupon->valeur;
        }
    }

    return null;
}
function couponCalcul($order,$product){
    if(isset($order->coupon)){
        if($order->coupon->products->contains($product->id)){

            $operand = ($order->coupon->type == 'product' ? 'percent' : 'minus');

            if($operand == 'percent') {
                return $product->price_cents - ($product->price_cents * ($order->coupon->value)/100);
            }

            if($operand == 'minus') {
                return $product->price_cents - $order->coupon->value;
            }
        }

        return ($product->price - ($product->price * ($order->coupon->value)/100)) / 100;
    }

    return $product->price_cents;
}

function setMaj($date,$what)
{
    return \Storage::disk('local')->put($what.'.txt', $date);
}

function getMaj($what)
{
    return \Storage::disk('local')->get($what.'.txt');
}

function priceConvert($price){

    if(isset($price['price_id']) && is_array($price['price_id'])){
        return ['prices' => collect($price['price_id'])->map(function ($p, $index) {
            $pieces = explode(':',$p);
            return [$pieces[0] => $pieces[1]];
        })->toArray()];
    }

    if(isset($price['price_id']) && (strpos($price['price_id'], ':') !== false)){
        $pieces = explode(':',$price['price_id']);
        return [$pieces[0] => $pieces[1]];
    }

    if(strpos($price, ':') !== false){
        $pieces = explode(':',$price);
        return [$pieces[0] => $pieces[1]];
    }
}

function converPriceUpdate($data){

    if(isset($data['price_id']) && (strpos($data['price_id'], ':') !== false)){
        $price_id = $data['price_id'];
        unset($data['price_id']);
        $pieces = explode(':',$price_id);
        $data[$pieces[0]] = $pieces[1];
    }

    return $data;
}

function filterEmptyArray($array){

    return array_map('array_filter', $array);

    return collect($array)->map(function ($item, $key) {

        if(is_array($item)){
            return collect($item)->reject(function ($child) {
                return empty($child);
            })->values()->toArray();
        }

        return !empty($item) ? $item : null;
    })->reject(function ($item) {
        return empty($item);
    })->values()->toArray();
}

function array_filter_recursive($input){
    if(is_array($input)){
        foreach ($input as &$value){
            if (is_array($value)){
                $value = array_filter_recursive($value);
            }
        }
        return array_filter($input);
    }

    return $input;
}

function getLinkId($data){

    if(isset($data['price_id']) && (strpos($data['price_id'], 'price_link_id:') !== false)){
        $pieces = explode(':',$data['price_id']);
        return $pieces[1];
    }

    if(isset($data['price_link_id'])){
        return $data['price_link_id'];
    }

    return null;
}

/*
 * For new implementation
 * we pass only the string price_link_id:1 or price_id:621
 * */
function isPriceLink($price_string){
    return (strpos($price_string, 'price_link_id:') !== false);
}

function getPriceId($price_string){
    $pieces = explode(':',$price_string);
    return $pieces[1];
}


function main_newsletter($id){
    $list = config('newsletter.lists');

    return in_array($id,$list);
}