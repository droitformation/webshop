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
            'adresse.first_name'     => 'required_without_all:user_id,adresse_id,adresse.company',
            'adresse.last_name'      => 'required_without_all:user_id,adresse_id,adresse.company',
            'adresse.adresse'        => 'required_without_all:user_id,adresse_id',
            'adresse.npa'            => 'required_without_all:user_id,adresse_id',
            'adresse.ville'          => 'required_without_all:user_id,adresse_id',
            'adresse.company'        => 'required_without_all:user_id,adresse_id,adresse.first_name,adresse.last_name',
        ], [
            'adresse.first_name.required_without_all'  => 'Une adresse (prénom) est requise sans utilisateur',
            'adresse.last_name.required_without_all'   => 'Une adresse (nom) est requise sans utilisateur',
            'adresse.adresse.required_without_all'     => 'Une adresse (adresse) est requise sans utilisateur',
            'adresse.npa.required_without_all'         => 'Une adresse (npa) est requise sans utilisateur',
            'adresse.ville.required_without_all'       => 'Une adresse (ville) est requise sans utilisateur',
            'adresse.company.required_without_all'     => 'Une nom d\'entreprise est requis sans nom/prénom',
        ]);

        $products = array_filter($request->input('order.products'));

        $validator->after(function($validator) use ($products) {
            if(empty($products))
            {
                $validator->errors()->add('order.products', 'Au moins un livre est requis');
            }
        });

        // Resend products along to refill form
        if ($validator->fails())
        {
            // resend adress if any
            $adresse = $request->input('adresse',[]);

            if(!empty($adresse))
            {
                // unset defaults, we can now test if we had other infos present, if not dont show form in view
                unset($adresse['canton_id'],$adresse['pays_id'],$adresse['civilite_id']);

                $adresse = (isset($adresse) ? array_filter(array_values($adresse)) : []);
            }

            $order = $request->input('order',[]);

            if(!empty($order))
            {
                $products = $helper->convertProducts($order);
            }

            return redirect('admin/order/create')->withErrors($validator)->with(['old_products' => $products, 'adresse' => $adresse])->withInput();
        }

        return $next($request);
    }
}
