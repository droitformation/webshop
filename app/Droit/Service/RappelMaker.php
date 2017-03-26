<?php

namespace App\Droit\Service;

use Clegginabox\PDFMerger\PDFMerger;

class RappelMaker{

    //Rappel generator
    public $worker;
    protected $merger;

    // Type order, inscription, abo
    protected $type;
    
    protected $outputDirectory;
    protected $pathToFiles;
    protected $boundFilename;
    protected $collection;
    
    public $makemore = false;

    public function __construct($worker, PDFMerger $merger)
    {
        $this->worker = $worker;
        $this->merger = $merger;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setOutputDirectory($outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;

        return $this;
    }

    public function setPathToFiles($pathToFiles)
    {
        $this->pathToFiles = $pathToFiles;

        return $this;
    }

    public function setBoundFilename($boundFilename)
    {
        $this->boundFilename = $boundFilename;

        return $this;
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    public function makeRappels()
    {
        if(!$this->collection->isEmpty()){
            foreach($this->collection as $item) {
                $this->make($item);
            }
        }

        return $this;
    }

    public function make($model)
    {
        $rappel = $model->list_rappel->sortBy('created_at')->last();

        if($this->makemore || !$rappel){
            $this->worker->generate($model);
        }
    }

    public function bind()
    {
        if(!$this->outputDirectory && ! $this->boundFilename){
            throw new \App\Exceptions\InfoMissingException('Paths for directory and filename missing');
        }
        
        $outputName = $this->outputDirectory.'/'.$this->boundFilename.'.pdf';

        // If directory doesn't exist make one
        if (!\File::exists($this->outputDirectory)) {\File::makeDirectory($this->outputDirectory);}

        // If file exist delete first
        if (!\File::exists($outputName)) {\File::delete($outputName);}

        // get all files
        $files = \File::files(public_path($this->pathToFiles));

        foreach($files as $file) {
            $this->merger->addPDF($file, 'all');
        }

        $this->merger->merge('file', $outputName, 'P');

        return $this;
    }

    public function notify($message)
    {
        // alert()->success($message);
    }
}