<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Service\UploadInterface;
use App\Droit\Document\Repo\DocumentInterface;

class UploadController extends Controller
{
    protected $upload;
    protected $document;

    public function __construct( UploadInterface $upload, DocumentInterface $document )
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
        $files = $this->upload->upload( $request->file('file') ,$path );

        if($files)
        {
            $array = [
                'success' => true,
                'files'   => $files['name'],
                'id'      => $request->input('id',null),
                'get'     => $request->all(),
                'post'    => $request->all()
            ];

            return response()->json($array);
        }

        return false;
    }

    public function uploadJS(Request $request)
    {
        $files = $this->upload->upload( $request->file('file') , 'uploads', 'newsletter');

        if($files)
        {
            return response()->json([
                'success' => true,
                'files'   => $files,
                'get'     => $request->all(),
                'post'    => $request->all()
            ], 200 );
        }
        return false;
    }


    public function uploadRedactor(Request $request)
    {
        $files = $this->upload->upload( $request->file('file') , 'files' );

        if($files)
        {
            $array = [
                'filelink' => url('/').'/files/uploads/'.$files['name'],
                'filename' => $files['name']
            ];

            return response()->json($array,200 );
        }

        return false;
    }

    public function imageJson()
    {
        $files = \Storage::disk('uploads')->files();
        $data   = [];
        $except = ['.DS_Store'];

        if(!empty($files))
        {
            foreach($files as $file)
            {
                if(!in_array($file,$except))
                {
                    $data[] = ['image' => url('/') . 'files/uploads/' . $file, 'thumb' => url('/') . '/uploads/' . $file, 'title' => $file];
                }
            }
        }

        return response()->json($data);
    }

    public function fileJson()
    {
        $files  = \Storage::disk('files')->files();
        $data   = [];
        $except = ['.DS_Store'];

        if(!empty($files))
        {
            foreach($files as $file)
            {
                if(!in_array($file,$except))
                {
                    $data[] = ['name' => $file, 'link' => url('/').'/files/uploads/'.$file, 'title' => $file];
                }
            }
        }

        return response()->json($data);
    }
}
