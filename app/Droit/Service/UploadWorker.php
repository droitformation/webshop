<?php namespace App\Droit\Service;

use App\Exceptions\FileUploadException;

class UploadWorker implements UploadInterface {

	/*
	 * upload selected file 
	 * @return array
	*/	
	public function upload( $file , $destination , $type = null ){

        $name = $file->getClientOriginalName();
        $ext  = $file->getClientOriginalExtension();

        $image_name = basename($name,'.'.$ext);

        //$slug = str_slug($image_name);
        //$slug = $slug.'_'.$type.rand(10,100000000);
        $newname = \Str::slug($image_name).'.'.$ext;

        // Get the name first because after moving, the file doesn't exist anymore
        $new  = $file->move($destination,$newname);

        $size = $new->getSize();
        $mime = $new->getMimeType();
        $path = $new->getRealPath();

        //resize
        if($type) {
            $sizes = \Config::get('size.'.$type);
            $this->resize( $path, null, $sizes['width'], $sizes['height']);
        }

        $newfile = array( 'name' => $newname ,'ext' => $ext ,'size' => $size ,'mime' => $mime ,'path' => $path  );

        return $newfile;
	}
	
	/*
	 * rename file 
	 * @return instance
	*/	
	public function rename( $file , $name , $path )
    {
		$newpath = $path.$name;
		
		return \Image::make( $file )->save($newpath);
	}
	
	/*
	 * resize file 
	 * @return instance
	*/	
	public function resize($path, $destination = null, $width = null , $height = null)
    {
        ini_set('memory_limit','2048M');

        $img = \Image::make($path)->orientate();

        $destination = $destination ? $destination : $path;

        // prevent possible upsizing
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($destination);
	}

    /*
     * crop file
     * @return instance
    */
    public function crop($image, $width, $height, $x, $y, $rotation = null)
    {
        // open file a image resource
        $img = \Image::make($image);

        // crop image
        $img->crop($width, $height, $x, $y);

        if($rotation)
        {
            $img->rotate($rotation);
        }

        $img->save();
    }

    /*
     * Scan directory
     * @return array
    */
    public function scan($dir){

        $files = array();

        // Is there actually such a folder/file?
        if(file_exists($dir))
        {

            foreach(scandir($dir) as $f) {

                if(!$f || $f[0] == '.') {
                    continue; // Ignore hidden files
                }

                if(is_dir($dir . '/' . $f)) {

                    // The path is a folder
                    $files[] = array(
                        "name" => $f,
                        "type" => "folder",
                        "path" => $dir . '/' . $f,
                        "items" => $this->scan($dir . '/' . $f) // Recursively get the contents of the folder
                    );
                }
                else {
                    // It is a file
                    $files[] = array(
                        "name" => $f,
                        "type" => "file",
                        "path" => $dir . '/' . $f,
                        "size" => filesize($dir . '/' . $f) // Gets the size of this file
                    );
                }
            }
        }

        return $files;
    }
    
    
}