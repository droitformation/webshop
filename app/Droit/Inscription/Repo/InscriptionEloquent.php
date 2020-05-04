<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Entities\Inscription as M;
use Carbon\Carbon;
use FontLib\Table\Type\kern;

class InscriptionEloquent implements InscriptionInterface{

    protected $inscription;

    public function __construct(M $inscription)
    {
        $this->inscription = $inscription;
    }

    public function getAll($nbr = null)
    {
        return $this->inscription->with(['groupe','groupe.user','groupe.user.adresses','user','user.adresses','duplicate','price','colloque.options','colloque.prices','user_options'])
            ->take($nbr)
            ->groupBy(\DB::raw('CASE WHEN group_id IS NOT NULL THEN group_id ELSE id END'))
            ->orderBy('created_at','DESC')
            ->get();
    }

    public function getMultiple(array $inscriptions)
    {
        return $this->inscription->whereIn('id',$inscriptions)
            ->with(['price','colloque','user','participant','groupe','rappels','group_rappels'])
            ->whereHas('price', function($q){
                $q->where('price','>', 0);
            })
            ->get();
    }

    public function getColloqe($colloque_id, $pagination = false, $filters = [])
    {
        $inscription = $this->inscription
            ->with(['user','user.adresses','groupe','groupe.user.adresses','price','options','user_options','user_options.option_groupe','colloque','colloque.options','colloque.documents','rappels'])
            ->colloque($colloque_id)
            ->filter($filters)
            ->groupBy('user_id')->groupBy('group_id')
            ->orderBy('created_at','DESC');

        return $pagination ? $inscription->paginate(30) : $inscription->get();
    }

    public function getByColloqueExport($id, $occurrences = [])
    {
        $occurrences = array_filter($occurrences);
        
        return $this->inscription
            ->where('colloque_id','=',$id)
            ->occurrence($occurrences)
            ->with(['user','user.adresses','price','user_options','colloque.options','occurrences'])
            //->groupBy('user_id')
            ->orderBy('created_at','DESC')->get();
    }

    public function getHasNoOccurences($id){
        return $this->inscription
            ->where('colloque_id','=',$id)
            ->doesntHave('occurrences')
            ->with(['user','user.adresses','price','user_options','colloque.options','occurrences'])
            ->orderBy('created_at','DESC')->get();
    }

    public function getByColloqueTrashed($id)
    {
        return $this->inscription->where('colloque_id','=',$id)->onlyTrashed()->groupBy('id')->get();
    }

    public function getRappels($id)
    {
        return $this->inscription->where('colloque_id','=',$id)
            ->whereNull('payed_at')
            ->with(['price','colloque','user','participant','groupe','duplicate', 'occurrences'])
            ->whereHas('price', function($q){
                $q->where('price','>', 0);
            })
            ->groupBy(\DB::raw('CASE WHEN group_id IS NOT NULL THEN group_id ELSE id END'))
            ->get();
    }

    public function getByUser($colloque_id,$user_id)
    {
        $inscription = $this->inscription
            ->with('rappels')->where('colloque_id','=',$colloque_id)
            ->where('user_id','=',$user_id)
            ->get();
        
        if(!$inscription->isEmpty())
        {
            return $inscription->first();
        }

        return false;
    }

    public function isRegistered($colloque_id,$user_id)
    {
        $inscription = $this->inscription->where('colloque_id','=',$colloque_id)
            ->where('user_id','=',$user_id)
            ->whereNull('group_id')
            ->get();

        if(!$inscription->isEmpty())
        {
            return $inscription->first();
        }

        return false;
    }

    public function findByNumero($numero, $colloque_id)
    {
        return $this->inscription->with(['price','colloque','user','rappels','user_options','user_options.option','groupe','participant'])
            ->where('colloque_id','=',$colloque_id)
            ->where(function($query) use ($numero) {

                $query->where('inscription_no','=',$numero);

                $query->orWhereHas('user', function($q) use ($numero){
                    $q->where('users.first_name','LIKE', '%'.$numero.'%')->orWhere('users.last_name','LIKE', '%'.$numero.'%');
                });

                $query->orWhereHas('participant', function($q) use ($numero){
                    $q->where('colloque_inscriptions_participants.name','LIKE', '%'.$numero.'%');
                });

                $query->orWhereHas('groupe', function($q) use ($numero){

                    $q->whereHas('user', function($second) use ($numero){
                        $second->where('users.first_name','LIKE', '%'.$numero.'%')
                            ->orWhere('users.last_name','LIKE', '%'.$numero.'%');
                    });
                });

            })
            ->groupBy(\DB::raw('CASE WHEN group_id IS NOT NULL THEN group_id ELSE id END'))
            ->paginate(20);
    }

    public function getByGroupe($groupe_id)
    {
       return $this->inscription->where('group_id','=',$groupe_id)->get();
    }

    public function find($id){

        return $this->inscription->with(['price','colloque','user','rappels','user_options','user_options.option','groupe','participant'])->find($id);
    }

    public function restore($id)
    {
        $restore = $this->inscription->withTrashed()->find($id);
        $exist   = $this->isRegistered($restore->colloque_id,$restore->user_id);
        
        if($exist) {
            throw new \App\Exceptions\InscriptionExistException('Impossible de restaurer une autre inscription pour cette personne existe déjà') ;
        }

        $restore->restore();

        return $restore;
    }

    public function hasPayed($user_id)
    {
        $days  = \Registry::get('inscription.days');

        $today = Carbon::now()->subDays($days);

        $notpayed = $this->inscription->where('status','!=','free')->whereNull('payed_at')
            ->where(function ($query) use ($user_id)
            {
                $query->whereHas('groupe', function ($query) use ($user_id){
                    $query->where('user_id','=',$user_id);
                })->orWhere('user_id','=',$user_id);
            });

        if($days > 0) {
            $notpayed->where('created_at','<=',$today);
        }

        $notpayed = $notpayed->get();

        return ($notpayed->isEmpty() ? true : false );
    }

    public function create(array $data){

        if( isset($data['user_id']) && $this->isRegistered($data['colloque_id'],$data['user_id']) )
        {
            throw new \App\Exceptions\RegisterException('Register failed');
        }
            
        $inscription = $this->inscription->create(array(
            'colloque_id'     => $data['colloque_id'],
            'user_id'         => (isset($data['user_id']) ? $data['user_id'] : null),
            'group_id'        => (isset($data['group_id']) ? $data['group_id'] : null),
            'rabais_id'       => $data['rabais_id'] ?? null,
            'inscription_no'  => $data['inscription_no'],
            'price_id'        => $data['price_id'],
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $inscription )
        {
            return false;
        }

        // Options
        if(isset($data['options']))
        {
            foreach($data['options'] as $option)
            {
                if(is_array($option))
                {
                    $id = key($option);
                    $inscription->options()->attach($id, ['inscription_id' => $inscription->id, 'reponse' => $option[$id]]);
                }
                else{
                    $inscription->options()->attach($option, ['inscription_id' => $inscription->id]);
                }
            }
        }

        // Occurrences
        if(isset($data['occurrences']))
        {
            foreach($data['occurrences'] as $occurrence)
            {
                $inscription->occurrences()->attach($occurrence, ['inscription_id' => $inscription->id]);
            }
        }

        // Options groupes
        if(isset($data['groupes']))
        {
            foreach($data['groupes'] as $option_id => $groupe_id)
            {
                $inscription->options()->attach($option_id, ['groupe_id' => $groupe_id, 'inscription_id' => $inscription->id]);
            }
        }

        if(isset($data['participant']) && !empty($data['participant']))
        {
            $participant = new \App\Droit\Inscription\Entities\Participant();
            $email = isset($data['email']) ? $data['email'] : null;
            $participant->create(['name' => $data['participant'], 'email' => $email, 'inscription_id' => $inscription->id ]);
        }

        return $inscription;

    }

    /*
     * For ajax calls, because we only want to update certain columns
     * */
    public function updateColumn(array $data)
    {
        $inscription = $this->inscription->find($data['id']);

        if(!$inscription)
        {
            return false;
        }
        
        $inscription->fill($data);

        if(isset($data['payed_at']) && !empty($data['payed_at']))
        {
            $valid = (Carbon::createFromFormat('Y-d-m', $data['payed_at']) !== false);

            $inscription->status = !$valid || null ? 'pending' : 'payed';
            $inscription->payed_at = $data['payed_at'];
        }

        if(empty($data['payed_at']))
        {
            $inscription->status   = 'pending';
            $inscription->payed_at = null;
        }
        
        $inscription->save();
        
        return $inscription;
    }
	
	 public function updateSend(array $data)
    {
        $inscription = $this->inscription->find($data['id']);

        if(!$inscription){
            return false;
        }
        
        $inscription->fill($data);
        $inscription->save();
        
        return $inscription;
    }
    
    public function update(array $data){

        $inscription = $this->inscription->findOrFail($data['id']);

        if(! $inscription)
        {
            return false;
        }

        $inscription->fill($data);

        if(isset($data['payed_at']) && !empty($data['payed_at']))
        {
            $valid = (Carbon::createFromFormat('Y-d-m', $data['payed_at']) !== false);

            $inscription->status = !$valid || null ? 'pending' : 'payed';
            $inscription->payed_at = $data['payed_at'];
        }

        if(empty($data['payed_at']))
        {
            $inscription->status   = 'pending';
            $inscription->payed_at = null;
        }

        $inscription->save();

        // occurrences
        // Remove all and re-attach if any
        $inscription->occurrences()->detach();
        
        if(isset($data['occurrences']))
        {
            foreach($data['occurrences'] as $occurrence)
            {
                $inscription->occurrences()->attach($occurrence, ['inscription_id' => $inscription->id]);
            }
        }

        // Options
        // Remove all and re-attach if any
        $inscription->options()->detach();

        if(isset($data['options']))
        {
            foreach($data['options'] as $option)
            {
                if(is_array($option))
                {
                    $id = key($option);
                    $inscription->options()->attach($id, ['inscription_id' => $inscription->id, 'reponse' => $option[$id]]);
                }
                else{
                    $inscription->options()->attach($option, ['inscription_id' => $inscription->id]);
                }
            }
        }

        // Options groupes
        if(isset($data['groupes']))
        {
            foreach($data['groupes'] as $option_id => $groupe_id)
            {
                $inscription->options()->detach($option_id);

                $inscription->options()->attach($option_id, ['groupe_id' => $groupe_id, 'inscription_id' => $inscription->id]);
            }
        }

        if(isset($data['participant']) && !empty($data['participant']))
        {
            $email = isset($data['email']) ? $data['email'] : null;
            $participant = new \App\Droit\Inscription\Entities\Participant();
            $participant->where('inscription_id','=',$inscription->id )->delete();
            $participant->create(['name' => $data['participant'], 'email' => $email, 'inscription_id' => $inscription->id ]);
        }

        return $inscription;
    }

    public function delete($id){

        $inscription = $this->inscription->find($id);

        return $inscription->delete();
    }
}
