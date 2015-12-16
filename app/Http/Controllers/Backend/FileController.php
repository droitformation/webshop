<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Service\FileWorkerInterface;

class FileController extends Controller
{
    protected $file;

    public function __construct(FileWorkerInterface $file)
    {
        $this->file = $file;
    }

    public function files(Request $request)
    {
        $images = ['jpg','jpeg','JPG','JPEG','png','gif'];
        $files  = $this->file->listDirectoryFiles($request->input('path'));

        echo view('manager.partials.files', ['path' => $request->input('path') ,'files' => $files, 'images' => $images]);
    }

}
