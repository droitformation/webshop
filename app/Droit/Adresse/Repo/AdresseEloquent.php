<?php namespace App\Droit\Adresse\Repo;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Adresse\Entities\Adresse as M;

class AdresseEloquent implements AdresseInterface{

	protected $adresse;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(M $adresse)
	{
		$this->adresse = $adresse;

		$this->format  = new \App\Droit\Helper\Format();
	}
	
	public function getAll(){
		
		return $this->adresse->where('user_id','=',0)->take(10)->skip(0)->get();	
	}

    public function search($term)
    {
		$terms = explode(' ',trim($term));

		if(count($terms) > 1)
		{
			return $this->adresse
				->where('email', 'like', '%'.$term.'%')
				->orWhere('first_name', 'like', '%'.$term.'%')
				->orWhere('last_name', 'like', '%'.$term.'%')
				->orWhere('company', 'like', '%'.$term.'%')
				->orWhere(function ($query) use($term,$terms) {
					if(count($terms) == 2)
					{
						$query->where(function ($query1) use ($terms) {
							$query1->where('first_name', 'like', '%'.$terms[0].'%')->where('last_name', 'like', '%'.$terms[1].'%');
						})->orWhere(function ($query2) use ($terms){
							$query2->where('first_name', 'like', '%'.$terms[1].'%')->where('last_name', 'like', '%'.$terms[0].'%');
						});
					}

					if(count($terms) == 3)
					{
						$query->where(function ($query1) use ($terms) {
							$query1->where('first_name', 'like', '%'.$terms[0].' '.$terms[1].'%')->where('last_name', 'like', '%'.$terms[2].'%');
						})->orWhere(function ($query2) use ($terms){
							$query2->where('first_name', 'like', '%'.$terms[0].'%')->where('last_name', 'like', '%'.$terms[1].' '.$terms[2].'%');
						});
					}
				})->get();
		}

		return $this->adresse->where('email', 'like', '%'.$term.'%')
			->orWhere('first_name', 'like', '%'.$term.'%')
			->orWhere('last_name', 'like', '%'.$term.'%')
			->orWhere('company', 'like', '%'.$term.'%')
			->get();

    }

	public function findByEmail($email)
	{
		$exist = $this->adresse->where('email', 'like', '%'.$email.'%')->get();

		return (!$exist->isEmpty() ? $exist->first() : null);
	}

    public function searchSimple($terms)
    {
        return $this->adresse->with(['user'])
            ->where('user_id','=',0)
            ->searchEmail($terms['email'])
            ->searchLastName($terms['last_name'])
            ->searchFirstName($terms['first_name'])
            ->get();
    }

    public function searchMultiple($terms, $each = false, $paginate = false)
    {
        $cantons         = (isset($terms['cantons']) ? $terms['cantons'] : null);
        $professions     = (isset($terms['professions']) ? $terms['professions'] : null);
        $pays            = (isset($terms['pays']) ? $terms['pays'] : null);
        $specialisations = (isset($terms['specialisations']) ? $terms['specialisations'] : null);
        $members         = (isset($terms['members']) ? $terms['members'] : null);

        $searchSpecialisation = ($each ? 'searchSpecialisationEach' : 'searchSpecialisation');
        $searchMember         = ($each ? 'searchMemberEach' : 'searchMember');

        if($paginate)
        {
            return $this->adresse->with(['user'])
                ->searchPays($pays)->searchCanton($cantons)->searchProfession($professions)
                ->$searchSpecialisation($specialisations)
                ->$searchMember($members)
                ->paginate($paginate);
        }
        else
        {
            return $this->adresse->with(['user'])
                ->searchPays($pays)->searchCanton($cantons)->searchProfession($professions)
                ->$searchSpecialisation($specialisations)
                ->$searchMember($members)
                ->get();
        }
    }

	public function duplicates()
	{
		$duplicates = DB::table('adresses')
			->select('adresses.id', 'adresses.email')
			->havingRaw('COUNT(*) > 1')
			->get();
	}

    public function getPaginate()
    {
        return $this->adresse->where('user_id','=',0)->orderBy('created_at','DESC')->take(5)->get();
    }

    public function getLast($nbr){
	
		return $this->adresse->orderBy('id', 'DESC')->take($nbr)->skip(0)->get();	
	}
	
	public function get_ajax( $sEcho , $iDisplayStart , $iDisplayLength , $sSearch = NULL , $iSortCol_0, $sSortDir_0){

		$columns = array( 'prenom', 'nom', 'email' , 'adresse',  'ville');

        $iTotal = $this->adresse->where('user_id','=',0)->get(array('id'))->count();
		
		if($sSearch)
		{
			$data = $this->adresse->where('user_id','=',0)
								  ->whereRaw('( prenom LIKE "%'.$sSearch.'%" OR nom LIKE "%'.$sSearch.'%" OR email LIKE "%'.$sSearch.'%" OR entreprise LIKE "%'.$sSearch.'%" OR adresse LIKE "%'.$sSearch.'%" )')
                                  ->orderBy($columns[$iSortCol_0], $sSortDir_0)
								  ->take($iDisplayLength)
								  ->skip($iDisplayStart)
								  ->get();
								    
			$iTotalDisplayRecords = $this->adresse->where('user_id','=',0)
								  ->whereRaw('( prenom LIKE "%'.$sSearch.'%" OR nom LIKE "%'.$sSearch.'%" OR email LIKE "%'.$sSearch.'%" OR entreprise LIKE "%'.$sSearch.'%" OR adresse LIKE "%'.$sSearch.'%" )')
								  ->get()
								  ->count();	
		}
		else
		{
			$data = $this->adresse->where('user_id','=',0)->orderBy($columns[$iSortCol_0], $sSortDir_0)->take($iDisplayLength)->skip($iDisplayStart)->get();
			
			$iTotalDisplayRecords = $iTotal;	
		}

		$output = array(
			"sEcho"                => $sEcho,
			"iTotalRecords"        => $iTotal,
			"iTotalDisplayRecords" => $iTotalDisplayRecords,
			"aaData"               => array()
		);
		
		$adresses = $data->toArray();
		
		foreach($adresses as $adresse)
		{
			$row = array();
		
			$row['prenom']  = $this->format->format_name($adresse['prenom']);
			$row['nom']     = $this->format->format_name($adresse['nom']);
			$row['email']   = "<a href=".url('admin/adresses/'.$adresse['id']).">".$adresse['email'].'</a>';
			$row['adresse'] = $this->format->format_name($adresse['adresse']);
			$row['ville']   = $this->format->format_name($adresse['ville']);
			
			$row['options'] = '<a class="btn btn-info edit_btn" type="button" href="'.url('admin/adresses/'.$adresse['id']).'">&Eacute;diter</a> ';
			// Reset keys
			$row = array_values($row);

			$output['aaData'][] = $row;
		}
		
		return json_encode( $output );
		
	}
	
	/**
	 * Return all adresse of the user 
	 *
	 * @return stdObject users
	 */
	public function find($id){
				
		return $this->adresse->with(['user','orders'])->find($id);
	}
	
	/**
	 * Return all infos for adrese to show
	 * We need:
	 * Adresse
	 * if it's an user adresse: user infos
	 * Specialisation
	 * Members
	 * 
	 * @return array
	*/
	public function show($id){
						
        $adresse = $this->find($id);
        $type    = $this->typeAdresse($id);
      
        $membres         = array();
        $specialisations = array();
        
        if($type == 1)
        {
       	 	$membres          = $this->members($id);
	   	 	$specialisations  = $this->specialisations($id);	        
        }
	
		return array( 'adresse' => $adresse , 'membres' => $membres , 'specialisations' => $specialisations , 'type' => $type );
	}

	/**
	 * Return if adresse is linked to user
	 *
	 * @return user id
	 */	
	public function isUser($adresse){
		
		$infos = $this->adresse->findOrFail($adresse);
		
		return $infos->user_id;			
	}

	public function setSpecialisation($adresse_id,$data)
	{
		$adresse = $this->adresse->find($adresse_id);
        $exist   = $adresse->specialisations->lists('id')->all();

        $adresse->specialisations()->sync(array_unique(array_merge($exist,$data)));
	}

	/**
	 * Return type of adresse
	 *
	 * @return  stdObject Collection of adresse
	 */		
	public function typeAdresse($adresse){
	
		$infos = $this->adresse->findOrFail($adresse);
		
		return $infos->type;			
	}

	public function create(array $data)
	{
		$adresse = $this->adresse->create(array(
			'civilite_id'   => $data['civilite_id'],
			'first_name'    => $this->format->format_name($data['first_name']),
			'last_name'     => $this->format->format_name($data['last_name']),
			'email'         => $data['email'],
			'company'       => (isset($data['company']) ? $data['company'] : null),
			'profession_id' => (isset($data['profession_id']) ? $data['profession_id'] : null),
			'telephone'     => (isset($data['telephone']) ? $data['telephone'] : null),
			'mobile'        => (isset($data['mobile']) ? $data['mobile'] : null),
			'fax'           => (isset($data['fax']) ? $data['fax'] : null),
			'adresse'       => $data['adresse'],
			'cp'            => (isset($data['cp']) ? $data['cp'] : null),
			'complement'    => (isset($data['complement']) ? $data['complement'] : null),
			'npa'           => $data['npa'],
			'ville'         => $data['ville'],
			'canton_id'     => (isset($data['canton_id']) ? $data['canton_id'] : null),
			'pays_id'       => (isset($data['pays_id']) ? $data['pays_id'] : 208),
			'type'          => (isset($data['type']) ? $data['type'] : 1),
			'user_id'       => (isset($data['user_id']) ? $data['user_id'] : 0),
			'livraison'     => (isset($data['livraison']) ? $data['livraison'] : 1),
			'created_at'    => date('Y-m-d G:i:s'),
			'updated_at'    => date('Y-m-d G:i:s')
		));
		
		if( ! $adresse )
		{
			return false;
		}
		
		return $adresse;
		
	}
	
	public function update(array $data){
		
		$adresse = $this->adresse->findOrFail($data['id']);	
		
		if( ! $adresse )
		{
			return false;
		}

        $adresse->fill($data);
		// Général

		if(isset($data['first_name'])){
			$adresse->first_name  = $this->format->format_name($data['first_name']);
		}
		if(isset($data['last_name'])){
			$adresse->last_name   = $this->format->format_name($data['last_name']);
		}

		$adresse->updated_at  = date('Y-m-d G:i:s');
		
		$adresse->save();	
		
		return $adresse;
	}

    /**
     *  Update a column
     */
    public function updateColumn($id , $column , $value){

        $adresse = $this->adresse->find($id);

        if( ! $adresse )
        {
            return false;
        }

        $adresse->$column = $value;
        $adresse->save();

        return $adresse;
    }

    /**
     * Change delivery adresse for user
     */
    public function changeLivraison($adresse_id , $user_id){

        $adresses = $this->adresseUser($user_id)->lists('id');

        foreach($adresses as $id)
        {
            // Find adresse
            $adresse = $this->adresse->find($id);
            // If it's the provided adresse we want to change set livraison to 1 else set to 0
            $livraison = ( $adresse_id == $id ? 1 : 0);
            $adresse->livraison = $livraison;
            $adresse->save();

        }

        return true;

    }

	public function delete($id){
	
		$adresse = $this->adresse->find($id);

		return $adresse->delete();
		
	}

}
