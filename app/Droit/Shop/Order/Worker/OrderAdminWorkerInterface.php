<?php

namespace App\Droit\Shop\Order\Worker;

interface OrderAdminWorkerInterface{

    public function prepare($commande);
    public function make($commande);
    public function total($commande, $proprety = 'price');
    public function insertOrder($commande);
    public function productIdFromForm($commande);
}