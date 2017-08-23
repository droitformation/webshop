<?php

namespace App\Http\Middleware;

use Closure;

class StripRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $helper = new \App\Droit\Helper\Helper();

        // Validate the adresse if any
        $validator = \Validator::make($request->all(), [
            'adresse.first_name' => 'required_without_all:adresse.company,user_id',
            'adresse.last_name'  => 'required_without_all:adresse.company,user_id',
            'adresse.company'    => 'required_without_all:adresse.first_name,adresse.last_name,user_id',
            'adresse.adresse'    => 'required_without:user_id',
            'adresse.npa'        => 'required_without:user_id',
            'adresse.ville'      => 'required_without:user_id',
            'adresse.email'      => 'email|max:255|unique:users,email|required_without:user_id',
            'adresse.password'   => 'min:6|required_without:user_id',
        ], [
            'adresse.first_name.required_without_all'  => 'Le prénom est requise sans utilisateur',
            'adresse.last_name.required_without_all'   => 'Une adresse (nom) est requise sans utilisateur',
            'adresse.company.required_without_all'     => 'Une nom d\'entreprise est requis sans nom/prénom',
            'adresse.adresse.required_without'         => 'Une adresse (adresse) est requise sans utilisateur',
            'adresse.npa.required_without'             => 'Une adresse (npa) est requise sans utilisateur',
            'adresse.password.required_without'        => 'Un mot de passe est requis sans utilisateur',
            'adresse.email.required_without'           => 'Un email est requis sans utilisateur',
        ]);

        $products = array_filter($request->input('order.products'));

        $validator->after(function($validator) use ($products) {
            if(empty($products)) {
                $validator->errors()->add('order.products', 'Au moins un livre est requis');
            }
        });

        // Resend products along to refill form
        if ($validator->fails()) {

            // resend adress if any
            $adresse = $request->input('adresse',[]);

            if(!empty($adresse)) {
                // unset defaults, we can now test if we had other infos present, if not dont show form in view
                unset($adresse['canton_id'],$adresse['pays_id'],$adresse['civilite_id']);

                $adresse = (isset($adresse) ? array_filter(array_values($adresse)) : []);
            }

            $order = $request->input('order',[]);

            if(!empty($order)) {
                $products = $helper->convertProducts($order);
            }

            $data =[
                'old_products'   => $products,
                'user_id'        => $request->input('user_id'),
                'shipping_id' => $request->input('shipping_id'),
                'tva'            => $request->input('tva'),
                'message'        => $request->input('message'),
                'paquet'         => $request->input('paquet'),
                'free'           => $request->input('free',null) ? 1 : null,
                'adresse'        => $adresse
            ];

            return redirect('admin/order/create')->withErrors($validator)->with($data)->withInput();
        }

        return $next($request);
    }
}
