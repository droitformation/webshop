<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Service\FileWorkerInterface;
use App\Droit\Service\UploadInterface;

use Illuminate\Support\Facades\Cache;

class FileController extends Controller
{
    protected $file;
    protected $upload;

    public function __construct(FileWorkerInterface $file, UploadInterface $upload)
    {
        $this->file   = $file;
        $this->upload = $upload;
    }

    public function files(Request $request)
    {
        $images = ['jpg','jpeg','JPG','JPEG','png','gif'];
        $files  = $this->file->listDirectoryFiles($request->input('path'), 'files');

        echo view('manager.partials.files', ['path' => $request->input('path') ,'files' => $files, 'images' => $images])->__toString();
    }
    
    public function getfiles(Request $request)
    {
        $files = $this->file->listDirectoryFiles($request->input('path'),$request->input('flat'));

        return response()->json(['files' => $files]);
    }

    public function gettree()
    {
        $directories = $this->file->manager();

        return response()->json(['directories' => $directories]);
    }

    public function tree()
    {
        $files = \Cache::rememberForever('files', function () {
            return $this->file->manager();
        });

        echo view('manager.partials.folders', ['files' => $files]);
    }

    public function delete(Request $request)
    {
        $file = $request->input('path');

        if (\File::exists($file))
        {
            \File::delete($file);

            echo true;
        }

        echo false;
    }

    public function crop(Request $request)
    {

        // imgW  => your new scaled image width
        // imgH  => your new scaled image height
        // imgX1  => top left corner of the cropped image in relation to scaled image
        // imgY1  => top left corner of the cropped image in relation to scaled image

        $image    = $request->input('imgUrl');
        $width    = $request->input('imgW');
        $height   = $request->input('imgH');
        $x        = $request->input('imgX1');
        $y        = $request->input('imgY1');
        $rotation = $request->input('rotation');

        $this->upload->crop($image, (int) $width, (int) $height, (int) $x, (int) $y, $rotation);

        echo json_encode(['status' => 'success', 'url' => $image]);

    }

    public function export()
    {
        return $this->file->treeDirectories(app_path());
    }
}
