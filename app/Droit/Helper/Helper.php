<?php namespace App\Droit\Helper;

use Carbon\Carbon;

class Helper {

    protected $upload;

    /**
     * Construct a new SentryUser Object
     */
    public function __construct()
    {

    }

	/*
	 * Dates functions
	*/

	// localized date format
    public static function formatDate($date) {
    
        $instance   = Carbon::createFromFormat('Y-m-d', $date); 
		setlocale(LC_TIME, 'fr_FR'); 							                   
		$formatDate = $instance->formatLocalized('%d %B %Y');
	
        return $formatDate;
    }
    
    //created_at field in DB
	public function getCreatedAtAttribute($value) { 
        //return $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $value);	
        return $carbonDate = date("d/m/Y", strtotime($value)); 
        //return $value;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    /*
	 * Files functions
	*/
    
	public function fileExistFormatLink( $path , $user , $event , $view , $name , $class = NULL){
		
		$link = $path.$user.'/'.$view.'_'.$event.'-'.$user.'.pdf';
		$url  = getcwd().'/'.$link;

		$add  = '';
		
		if ( \File::exists($url) )
		{
			$asset = asset($link);

			if($class){
				$add = ' class="'.$class.'" ';
			}
			
			return '<a target="_blank" href="'.$asset.'"'.$add.'>'.$name.'</a>';	
		}
		
		return '';
	}
	
	/* Get mime-type of file */
	public function getMimeType($filename)
	{
	    $mimetype = false;
	    
	    if(function_exists('finfo_fopen')) 
	    {
	       $mimetype = finfo_fopen($filename);
	    } 
	    elseif(function_exists('getimagesize')) 
	    {
	       $mimetype = getimagesize($filename);
	    } 
	    elseif(function_exists('exif_imagetype')) 
	    {
	       $mimetype = exif_imagetype($filename);
	    } 
	    elseif(function_exists('mime_content_type')) 
	    {
	       $mimetype = mime_content_type($filename);
	    }
	    
	    return $mimetype['mime'];
	}

    
	public function fileExistFormatImage( $path , $width ){
		
		$url  = getcwd().$path;		
		$add  = '';
		
		$ext = array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF');
		
		if ( \File::exists($url) ){
			
			$extension = \File::extension($url);
			
			if ( in_array( $extension , $ext )  )
			{
				$asset = asset($path);
				
				return '<img src="'.$asset.'" alt="" width="'.$width.'px" />';	
			}	
		}
	}
	
	/*
	 * Misc functions
	*/
    
    public static function ifExist(&$argument, $default="") {
    
	    if(!isset($argument)) {
	       $argument = $default;
	       return $argument;
	    }
	   
	    $argument = trim($argument);
	   
	    return $argument;
	}
	
	public static function preparePrice($price){
		
		$prepared = explode('.', $price);
		
		return $prepared;
	}
	
	public function limit_words($string, $word_limit){
	
		$words = explode(" ",$string);
		$new = implode(" ",array_splice($words,0,$word_limit));
		if( !empty($new) ){
			$new = $new.'...';
		}
		return $new;
	}

	/**
	 * Format name with hyphens or liaisons
	 *
	 * @return string
	 */			
	public function format_name($string){
	
			// liaisons word
			$liaison = array('de','des','du','von','dela','del','le','les','la','sur');
			$words   = array();
			$final   = '';
			// explode the name by space
			$mots =  explode(' ', $string);
						
			if(count($mots) > 0)
			{	
				// si mots composé plus de 1 mot				
				foreach($mots as $i => $mot)
				{
			   		// si il existe un hyphen
		   			if (strpos($mot,'-') !== false) {
		   				
		   				// 2eme explode delimiteur hyphens
		   				$parts =  explode('-', $mot);
		   				
		   				// tout en minuscule
		   				$parts = array_map('strtolower', $parts);			   				
		   				$nbr   = count($parts);
		   				$loop  = 1;
		   				
		   				foreach($parts as $part){
			   	  	
					   	  	  if( !in_array($part, $liaison))
					   	  	  {						   	  	  	
						   	  	 $part = ucfirst($part);
					   	  	  }
					   	  		
						   	  $words[] = $part;
						   	  
						   	  if($loop < $nbr)
						   	  {
							   	 $words[] = '-'; // remet delmiteur hyphen 
						   	  }
						   	  
						   	  $loop++;  
					   	}
		   			}
		   			else
		   			{ 
		   				// sans hyphens mais plusieurs mots
			   			$mot = strtolower($mot);
			   			
	   					if( !in_array($mot, $liaison) || $i == 0)
	   					{
						   	$mot = ucfirst($mot);
					   	}
					   	  		
						$words[] = $mot;
						$words[] = ' '; // remet delmiteur espace
		   			}
				}
	
				$final = implode('',$words);				
			}
			else
			{ 
				// un seul mot
	   			$final = $string;
			}
			
		return $final;
	}
	
	/*
	 * String manipulation functions
	 *
	*/
	
	/*  Remove accents */
	
	public function _removeAccents ($text) {
	    $alphabet = array(
	        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
	        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
	        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
	        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
	        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
	        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
	        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'ü'=>'u'
	    );
	
	    $text = strtr ($text, $alphabet);
	
	    // replace all non letters or digits by -
	    $text = preg_replace('/\W+/', '', $text);
	
	    return $text;
	}
	
	/*
	 * remove html tags and non alphanumerics letters	
	*/
	public function _removeNonAlphanumericLetters($sString) {
	     //Conversion des majuscules en minuscule
	     $string = strtolower(htmlentities($sString));
	     //Listez ici tous les balises HTML que vous pourriez rencontrer
	     $string = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml|grave);/", "$1", $string);
	     //Tout ce qui n'est pas caractère alphanumérique  -> _
	     $string = preg_replace("/([^a-z0-9]+)/", "_", html_entity_decode($string));
	     return $string;
	}
	
	/*
	 * Array functions
	*/	
	
	// add arrays together
	public function addArrayToArray($array1 , $array2){
		
		return array_merge($array1,$array2);
		
	}
	
	// Insert new pair key/value in array at first place
	public function insertFirstInArray( $key , $value , $array ){
		
		$insert = array( $key => $value );		
		$new    = $insert + $array;
		
		return $new;
	}
	
	/*  Sort array by key  */		
	public function knatsort(&$karr)
	{
	    $kkeyarr    = array_keys($karr);
	    $ksortedarr = array();
	    	    
	    natcasesort($kkeyarr);
	    
	    foreach($kkeyarr as $kcurrkey)
	    {
	        $ksortedarr[$kcurrkey] = $karr[$kcurrkey];
	    }
	    
	    $karr = $ksortedarr;
	    
	    return true;
	}
	
	/* Sort by keys */
	public function keysort($karr){
	    
	    $ksortedarr = array();
	    
	    foreach($karr as $id => $kcurrkey)
	    {
	    	// remove accents
	    	$currkey = $this->_removeAccents($kcurrkey);
	    	$currkey = strtolower($currkey);
	    	
	        $ksortedarr[$currkey]['title'] = $kcurrkey;
	        $ksortedarr[$currkey]['id']    = $id;
	    }
	    
	    return $ksortedarr;

	}
	
	/* Find all items in array */
	public function findAllItemsInArray( $in , $search ){
		
		$need = count($in);
		$find = count(array_intersect($search, $in));
		
		if($need == $find)
		{
			return TRUE;
		}
		
		return FALSE;	
	}

    public function convertLink($link){

        $text  = preg_replace('/<link[^>]*?>([\\s\\S]*?)<\/link>/','\\1', $link);
        $strip = array("<link", "</link>", "_blank", ">" ,"external-link-new-window", $text);
        $href  = str_replace($strip, "", $link);

        return '<a href="'.$href.'" target="_blank">'.$text.'</a>';

    }

    /**
     * Compare two arrays
     *
     * @return
     */
    public function compare($selected, $result)
    {
        $compare = array_intersect($selected, $result);

        return ($compare == $selected ? true : false);
    }

    /**
     * Get array of string using prefix
     *
     * @return
     */
    public function getPrefixString($array, $prefix)
    {
        $items = array();

        if(!empty($array)){
            foreach($array as $item){
                preg_match('/'.$prefix.'(.*)/', $item, $results);
                if(isset($results[1])){
                    $items[] = $results[1];
                }
            }
        }

        return $items;
    }

    public function prepareSearch($search){

        // decode spécial char
        $search =  htmlspecialchars_decode($search);

        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $search, $matches);

        $recherche = $matches[0];

        foreach($recherche as $rech)
        {
            // there is quotes "
            if (preg_match('/\"([^\"]*?)\"/', $rech, $m))
            {
                $string = $m[1];
                $string = str_replace('"', '', $string);
                $item   = str_replace('"', '', $string);

                $find[] = $item;
            }
            else // no quotes
            {
                $string = trim($rech);

                if( $string != '')
                {
                    $find[] = $string;
                }
            }
        }

        return $find;

    }

	public function sanitizeUrl($url){

		if (!preg_match("/^(http|https|ftp):/", $url)) {
			$url = 'http://'.$url;
		}

		return $url;
	}

    /**
     * Content fonctions
     */

    public function sortArrayByArray(Array $array, Array $orderArray) {
        $ordered = array();

        foreach($orderArray as $key)
        {
            if(array_key_exists($key,$array))
            {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }

        return $ordered + $array;
    }

    public function withEmpty($selectList, $emptyLabel = '') {
        return array('' => $emptyLabel) + $selectList;
    }

    public function convertSerializedData($data){

        $user = [];
        if(!empty($data))
        {
            foreach($data as $info)
            {
                $user[$info['name']] = $info['value'];
            }
        }

        return $user;
    }

    public function convertAutocomplete($results){
        $data = [];
        if(!$results->isEmpty())
        {
            foreach($results as $result)
            {
                $data[] = [
                    'label'   => $result->name ,
                    'desc'    => $result->email,
                    'adresse' => $result->adresse_facturation,
                    'value'   => $result->id
                ];
            }
        }
        return $data;
    }

    public function groupInscriptionCollection($collection){

        $grouped = [];

        if(!$collection->isEmpty())
        {
            foreach($collection as $inscription)
            {
                if($inscription->group_id)
                {
                    $grouped[$inscription->group_id][] = $inscription;
                }
                else
                {
                    $grouped[] = $inscription;
                }
            }
        }

        return $grouped;
    }

}