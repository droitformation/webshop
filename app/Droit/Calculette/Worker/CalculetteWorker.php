<?php namespace App\Droit\Calculette\Worker;

use App\Droit\Calculette\Worker\CalculetteWorkerInterface;

use App\Droit\Calculette\Entities\Calculette_ipc as CI;
use App\Droit\Calculette\Entities\Calculette_taux as CT;

use Carbon\Carbon;

class CalculetteWorker implements CalculetteWorkerInterface {

	protected $taux;
	protected $ipc;
	
	// Class expects an Eloquent model
	public function __construct(CT $taux , CI $ipc)
	{
		$this->taux = $taux;
		$this->ipc  = $ipc;	
	}
	
	public function calculer($canton, $date , $loyer){
			
		$taux_depart  = $this->taux_depart($date,$canton);
		$taux_actuel  = $this->taux_actuel();
		$taux_date    = $this->taux_date_actuel();
		$ipc_depart   = $this->ipc_depart($date);
		$ipc_actuel   = $this->ipc_actuel();
		$ipc_date     = $this->ipc_date_actuel();		
		$new          = $this->calcul($canton, $date , $loyer); 
		
		$newloyer   = number_format($new,2,'.',"'");
		$difference = number_format($newloyer-$loyer,2,'.',"'");
		
		setlocale(LC_ALL, 'fr_FR');

		$taux_date = Carbon::parse($taux_date)->formatLocalized('%B %Y');
		$ipc_date  = Carbon::parse($ipc_date)->formatLocalized('%B %Y');

		$ipc_date = preg_match('!!u', $ipc_date) ? $ipc_date : utf8_encode($ipc_date);
		
		$calcul = array(
			'taux_depart' => $taux_depart,
			'taux_actuel' => $taux_actuel,
			'taux_date'   => $taux_date,
			'ipc_depart'  => $ipc_depart,
			'ipc_actuel'  => $ipc_actuel,
			'ipc_date'    => $ipc_date,
			'difference'  => $difference,
			'loyer'       => $newloyer,
			'result'      => 'ok'
		);
		
		return $calcul;
	}
	
	public function calcul($canton, $date_depart, $loyer_actuel)
    {
		// taux départ,taux actuel 
		$taux_depart = $this->taux_depart($date_depart,$canton); 
		$taux_actuel = $this->taux_actuel();
		
		// ipc départ, ipc actu
		$ipc_depart = $this->ipc_depart($date_depart);
		$ipc_actuel = $this->ipc_actuel();
		
		// calcul		
		$taux_variation_ipc = (($ipc_actuel-$ipc_depart) * 100)/$ipc_depart;
		$augmentation_ipc   = $loyer_actuel * ($taux_variation_ipc / 100) * 0.4;
		$loyer_augmente_ipc = $loyer_actuel + $augmentation_ipc;
		
		$nouveau_loyer = $loyer_augmente_ipc;
		
		$tDepart = $taux_depart;
		$tActuel = $taux_actuel;
		
		$tFinal20 = 0;
		$tFinal25 = 0;
		$tFinal30 = 0;
		
		if($tDepart != $tActuel) 
		{
			$tMax = max($tDepart,$tActuel) * 4;
			$tMin = min($tDepart,$tActuel) * 4;

			$tMax1 = $tMax - 20;
			$tMin1 = $tMin - 20;
			$tDif1 = $tMax1-$tMin1;
			
			if($tMin1 < 0 && $tMax1 < 0)
            {
				$tFinal30 = $tDif1;
			} 
			else 
			{
				if($tMin1 < 0)
				{
					$tFinal30 = $tMin1 * -1;
					$tMin1 = 0;
				}
				
				$tMax2 = $tMax1 - 4;
				$tMin2 = $tMin1 - 4;
				$tDif2 = $tMax2 - $tMin2;
				
				if($tMin2 < 0 && $tMax2 < 0)
                {
					$tFinal25 = $tDif2;
				} 
				else 
				{
					if($tMin2<0) 
					{
						$tFinal25 = $tMin2 * -1;
						$tFinal20 = $tMax2;
					} 
					else 
					{
						$tFinal20 = $tDif2;
					}
				}
			}
			
			$tauxFinal = ($tFinal20 * 2) + ($tFinal25 * 2.5) + ($tFinal30 * 3);
			
			$isBaisse = ($tDepart > $tActuel) ? true : false;
			
			if($isBaisse) 
			{
				$tauxFinal = ($tauxFinal * 100) / ($tauxFinal + 100);
				$tauxFinal = $tauxFinal * -1;
			}
			
			$nouveau_loyer += $nouveau_loyer * ($tauxFinal / 100);
		}
		
		return $nouveau_loyer;	

	}

	public function taux_depart($date_depart,$canton)
	{
		$taux = $this->taux->where('start_at','<=',$date_depart)
			->where(function($query) use ($canton)
			{
				$query->where('canton', '=', $canton)->orWhere('canton', '=', 'u');
			})
			->orderBy('start_at', 'DESC')
			->get(['taux'])
			->first();

		if($taux)
		{
			return $taux->taux;
		}
	}
	
	public function taux_actuel()
	{
		$taux = $this->taux->where('canton', '=', 'u')->orderBy('start_at', 'DESC')->get(['taux'])->first();

		if($taux)
		{
			return $taux->taux;
		}
	}
	
	public function taux_date_actuel()
	{
		$taux = $this->taux->where('canton', '=', 'u')->orderBy('start_at', 'DESC')->get(['start_at'])->first();

		if($taux)
		{
			return $taux->start_at;
		}
	}
	
	public function ipc_depart($date_depart)
	{
		$ipc = $this->ipc->where('start_at','<=',$date_depart)->orderBy('start_at', 'DESC')->get(['indice'])->first();

		if($ipc)
		{
			return $ipc->indice;
		}
	}
	
	public function ipc_actuel()
	{
		$ipc = $this->ipc->orderBy('start_at', 'DESC')->get(['indice'])->first();

		if($ipc)
		{
			return $ipc->indice;
		}
	}
	
	public function ipc_date_actuel()
	{
		$ipc = $this->ipc->orderBy('start_at', 'DESC')->get(['start_at'])->first();

		if($ipc)
		{
			return $ipc->start_at;
		}
	}

}

