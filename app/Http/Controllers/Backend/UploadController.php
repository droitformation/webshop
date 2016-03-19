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
        $_file = $request->file('file',null);

        if($_file)
        {
            $path   = $request->input('path').'/'.$request->input('type');
            $resize = $request->input('type') == 'illustration' ? 'illustration' : null;
            $files  = $this->upload->upload( $request->file('file') ,$path, $resize);

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

        return redirect()->back()->with(array('status' => 'danger', 'message' => 'Veuillez choisir un document'));
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
        $files = $this->upload->upload( $request->file('file') , 'files/uploads', 'newsletter');

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
        // thumb for redactor filemanager
        //$this->upload->upload( $request->file('file') , 'files/uploads/thumbs', 'thumbs');

        $files = $this->upload->upload( $request->file('file') , 'files/uploads' );

        \File::copy(public_path('/files/uploads/'.$files['name']), public_path('/files/uploads/thumbs/'.$files['name']));

        $sizes = \Config::get('size.thumbs');

        $this->upload->resize(  public_path('/files/uploads/thumbs/'.$files['name']), '', $sizes['width'], $sizes['height']);

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

    public function uploadFileRedactor(Request $request)
    {
        $files = $this->upload->upload( $request->file('file') , 'files/uploads' );

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
        $files = \Storage::disk('fileuploads')->files();
        $data   = [];

        if(!empty($files))
        {
            foreach($files as $file)
            {
                $mime = \File::mimeType(public_path('files/uploads/'.$file));

                if(substr($mime, 0, 5) == 'image')
                {
                    $data[] = ['image' => asset('files/uploads/'.$file), 'thumb' => asset('files/uploads/thumbs/'.$file), 'title' => $file];
                }
            }
        }

        return response()->json($data);
    }

    public function fileJson()
    {
        $files  = \Storage::disk('fileuploads')->files();
        $data   = [];

        if(!empty($files))
        {
            foreach($files as $file)
            {
                $mime = \File::mimeType(public_path('files/uploads/'.$file));

                if(substr($mime, 0, 5) != 'image')
                {
                    $data[] = ['name' => $file, 'link' => asset('files/uploads/'.$file) , 'title' => $file];
                }
            }
        }

        return response()->json($data);
    }
}
