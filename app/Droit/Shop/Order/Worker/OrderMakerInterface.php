<?php

namespace App\Droit\Shop\Order\Worker;


interface OrderMakerInterface
{
    /*
    * Prepare data and insert order in DB
    * We can pass shipping already calculated and coupon from shop
    * Generate a invoice in pdf and add messages and/or change TVA
    * */
    public function make($commande, $shipping = null, $coupon = null);

    /*
    * Prepare data for order
    * From frontend and backend
    * */
    public function prepare($order = null, $shipping = null, $coupon = null);

    /*
    * Insert new order
    * Save the cart if any
    * */
    public function insert($data);

    /*
     * Reset qty of products when canceling order
     * */
    public function resetQty($order,$operator);

    /*
    * Form admin the values for rabais and free can be send as null in the order array
    * Unset empty elements to create new order
    * */
    public function removeEmpty($items);

    /*
    * Count qty for each product in order
    * */
    public function getCountProducts($order);

    /*
    * Get qty for each product
    *
    * Return [21 => 1, 3 => 2, 223 => 1] (product_id => qty)
    * */
    public function getQty($order);

    /*
    * Get products id (from qty) from cart
    *
    * Return [55,55,54,34]
    * */
    public function getProductsCart($cart);

    /*
    * Get products form prepared for relation
    *
    * Return
    * */
    public function getProducts($order);

    /*
     *  Total for price and weight
     * */
    public function total($commande, $proprety = 'price');

    /*
     * Get Shipping from the weigth or test if free request
     **/
    public function getShipping($order);

    /*
      * Update Order products with coupon
      **/
    public function updateOrder($order, $shipping_id, $coupon = null);

    /**
     * Calculat price from product and apply coupon discount
     * IIT
     * @return float
     * */
    public function calculPriceWithCoupon($product,$coupon,$operand);
}