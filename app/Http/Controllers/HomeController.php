<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Site\Repo\SiteInterface;

class HomeController extends Controller
{
    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
    }

    /**
     * Send contact message
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->input('id')){
            if($request->input('id') == 275){
                return redirect('pubdroit/colloque');
            }

            return redirect('pubdroit');
        }
        
        return redirect('pubdroit');
    }
}
