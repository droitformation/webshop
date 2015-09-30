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
        $path  = $request->input('path').'/'.$request->input('type');
        $files = $this->upload->upload( $request->file('file') ,$path);

        if($files)
        {
            $array = [
                'success' => true,
                'files'   => $files['name'],
                'get'     => $request->all(),
                'post'    => $request->all()
            ];

            return response()->json($array);
        }

        return false;
    }
}
