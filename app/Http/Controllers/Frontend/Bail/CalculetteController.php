<?php

namespace App\Http\Controllers\Frontend\Bail;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Droit\Calculette\Worker\CalculetteWorkerInterface;

class CalculetteController extends Controller
{
    protected $calculette;

    public function __construct(CalculetteWorkerInterface $calculette)
    {
        $this->calculette  = $calculette;
    }

    public function loyer(Request $request)
    {
        $data = $request->all();

        if(!empty( $data ))
        {
            $date = Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateTimeString();

            return $this->calculette->calculer($request->input('canton'), $date, $request->input('loyer'));
        }

        return [];
    }
}
