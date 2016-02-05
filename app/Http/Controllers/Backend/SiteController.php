<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Service\UploadInterface;

class SiteController extends Controller
{
    protected $upload;
    protected $site;

    public function __construct( UploadInterface $upload, SiteInterface  $site )
    {
        $this->upload = $upload;
        $this->site   = $site;
    }

    /**
     * @return Response
     */
    public function show($id)
    {
        $site = $this->site->find($id);

        return view('backend.sites.show')->with(['site' => $site]);
    }
}
