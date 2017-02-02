<?php namespace App\Droit\Service;

interface UploadInterface{

	/*
	 * upload selected file 
	 * @return array
	*/	
	public function upload( $file , $destination , $type = null);
	
	/*
	 * rename file 
	 * @return array
	*/	
	public function rename( $file , $name , $path );
	
	/*
	 * resize file 
	 * @return array
	*/	
	public function resize( $path, $destination = null, $width = null , $height = null);


    /*
     * resize crop
     * @return array
    */
    public function crop($image , $width, $height, $x, $y, $rotation = null);

}