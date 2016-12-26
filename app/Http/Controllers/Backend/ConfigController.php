<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{

    public function colloque()
    {
        return view('backend.config.colloque');
    }
    
    public function abo()
    {
        return view('backend.config.abo');
    }
    
    public function shop()
    {
        return view('backend.config.shop');
    }
    
    public function store(Request $request)
    {
        $settings = $request->all();
        unset($settings['_token']);

        \Registry::store($settings);

        alert()->success('Configuration mises à jour');

        return redirect()->back();
    }
}
