<?php

namespace App\Droit\Newsletter\Worker;

use Illuminate\Http\UploadedFile;

interface ImportWorkerInterface
{
    public function setFile(UploadedFile $file);
    public function uploadAndRead();
    public function import($data, UploadedFile $file);
    public function subscribe($results, $list = null);
    public function read($file);
    public function storeToCsv($data);
    public function sync($file,$list);
}