<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Service\UploadWorker;
use App\Droit\Document\Repo\DocumentInterface;

class UploadController extends Controller
{
    protected $upload;
    protected $document;

    public function __construct( UploadWorker $upload, DocumentInterface $document )
    {
        $this->upload   = $upload;
        $this->document = $document;
    }

    public function uploadFile(Request $request)
    {
        $path  = $request->input('path').'/'.$request->input('type');
        $files = $this->upload->upload( $request->file('file') ,$path);

        if($files)
        {
            $this->document->create(
                [
                    'colloque_id' => $request->input('colloque_id'),
                    'type'        => $request->input('type'),
                    'path'        => $files['name'],
                    'titre'       => $request->input('titre')
                ]);

            return redirect()->back()->with(array('status' => 'success', 'message' => 'Document ajouté'));
        }

        return redirect()->back()->with(array('status' => 'danger', 'message' => 'Problème avec le document'));
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
