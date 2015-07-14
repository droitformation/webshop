<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{

    public function postPayment()
    {
        $params = array(
            'cancelUrl'   => url('/').'/cancel_order',
            'returnUrl'   => url('/').'/payment_success',
            'name'		  => Input::get('name'),
            'description' => Input::get('description'),
            'amount' 	  => Input::get('price'),
            'currency' 	  => Input::get('currency')
        );

        session()->put('params', $params);
        session()->save();

        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername('paypal account');
        $gateway->setPassword('paypal password');
        $gateway->setSignature('AiPC9BjkCyDFQXbSkoZcgqH3hpacASJcFfmT46nLMylZ2R-SV95AaVCq');

        $gateway->setTestMode(true);

        $response = $gateway->purchase($params)->send();

        if ($response->isSuccessful()) {

            // payment was successful: update database
            print_r($response);

        } elseif ($response->isRedirect()) {

            // redirect to offsite payment gateway
            $response->redirect();

        } else {

            // payment failed: display message to customer
            echo $response->getMessage();

        }
    }

    public function getSuccessPayment()
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername('paypal account');
        $gateway->setPassword('paypal password');
        $gateway->setSignature('AiPC9BjkCyDFQXbSkoZcgqH3hpacASJcFfmT46nLMylZ2R-SV95AaVCq');
        $gateway->setTestMode(true);

        $params = Session::get('params');

        $response = $gateway->completePurchase($params)->send();
        $paypalResponse = $response->getData(); // this is the raw response object

        if(isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {

            // Response
            // print_r($paypalResponse);

        } else {

            //Failed transaction

        }

        return View::make('result');
    }
}
