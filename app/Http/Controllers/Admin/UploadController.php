<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Service\UploadWorker;

class UploadController extends Controller
{
    public function __construct( UploadWorker $upload )
    {
        $this->upload = $upload;
    }

    public function upload(Request $request)
    {
        $files = $this->upload->upload( $request->file('file') , $request->input('path'));

        if($files)
        {
            $array = [
                'success' => true,
                'files'   => $request->input('file'),
                'get'     => $request->all(),
                'post'    => $request->all()
            ];

            return response()->json($array);
        }

        return false;
    }
}
