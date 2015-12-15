<?php

namespace App\Droit\Service;

interface FileWorkerInterface{

    public function authorisized();
    public function tree($source_dir, $directory_depth = 0, $hidden = FALSE);
    public function manager();
    public function listActionFiles($dir);
    public function listDirectoryFiles($dir);
}