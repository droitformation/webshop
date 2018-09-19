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

                alert()->success('Document ajouté');

                return redirect()->back();
            }

            alert()->warning('Problème avec le document');

            return redirect()->back();
        }

        alert()->warning('Veuillez choisir un document');

        return redirect()->back();
    }

    // For dropzone manager
    public function upload(Request $request)
    {
        $path  = $request->input('path').'/'.$request->input('type');
        $files = $this->upload->upload( $request->file('file') , $path , 'newsletter');

        if($files)
        {
            $array = [
                'success'  => true,
                'filename' => $files['name'],
                'id'       => $request->input('id',null),
                'get'      => $request->all(),
                'post'     => $request->all()
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

    public function uploadNewsletter(Request $request)
    {
        $imageData = $request->input('image');
        $fileName  = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

        $sizes = config('size.newsletter');
        \Image::make($request->input('image'))->orientate()
            ->resize($sizes['width'], $sizes['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('files/uploads/').$fileName);

        return response()->json(['error' => false, 'name' => $fileName]);
    }

    public function uploadRedactor(Request $request)
    {
        $file = $this->upload->upload( $request->file('file')[0] , 'files/uploads' );

        if($file)
        {
            $mime = \File::mimeType(public_path('files/uploads/'.$file['name']));

            if(substr($mime, 0, 5) == 'image')
            {
                \File::copy(public_path('files/uploads/'.$file['name']), public_path('files/uploads/thumbs/'.$file['name']));
                $sizes = \Config::get('size.thumbs');

                $this->upload->resize( public_path('files/uploads/thumbs/'.$file['name']), null, $sizes['width'], $sizes['height']);
            }

            $array = ['file' =>
                [
                    'url'  => secure_asset('/files/uploads/'.$file['name']),
                    'name' => $file['name'],
                    'id'   => md5(date('YmdHis'))
                ]
            ];

            return response()->json($array);
        }

        return false;
    }

    public function uploadFileRedactor(Request $request)
    {
        $file = $this->upload->upload( $request->file('file')[0] , 'files/uploads' );

        if($file)
        {
            $array = ['file' =>
                [
                    'url'  => secure_asset('/files/uploads/'.$file['name']),
                    'name' => $file['name'],
                    'id'   => md5(date('YmdHis'))
                ]
            ];

            return response()->json($array);
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
                    $data[] = ['url' => secure_asset('/files/uploads/' . $file), 'thumb' => secure_asset( '/files/uploads/' . $file) , 'title' => $file];
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
                    $data[] = ['name' => $file, 'url' => secure_asset('/files/uploads/'.$file), 'title' => $file];
                }
            }
        }

        return response()->json($data);
    }
}
