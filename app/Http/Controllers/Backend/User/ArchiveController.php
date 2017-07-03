<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;

use App\Droit\Adresse\Worker\AdresseWorkerInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Abo\Repo\AboUserInterface;

class ArchiveController extends Controller
{
    protected $order;
    protected $inscription;
    protected $abo;

    public function __construct(OrderInterface $order, InscriptionInterface $inscription, AboUserInterface $abo)
    {
        $this->order   = $order;
        $this->inscription = $inscription;
        $this->abo = $abo;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index(Request $request)
    {
        $model = $request->input('model','inscription');
        $year  = $request->input('year',date('Y'));
        $month = $request->input('month',date('m'));

        $list = $this->$model->getYear($year,$month);
 
        return view('backend.archives.index')->with([
            'list'  => $list,
            'year'  => $year,
            'month' => $month,
            'model' => $model,
        ]);
    }

    public function year(Request $request)
    {
        $model = $request->input('model','inscription');
        $year  = $request->input('year',date('Y'));

        $list = $this->$model->getYear($year);

        $list = $list->groupBy(function ($item, $key) {
            return $item->created_at->month;
        })->map(function ($group, $key) {
            return $group->count();
        });

        $list = array_pad_keys($list->toArray(),12);

        return view('backend.archives.year')->with([
            'list'  => $list,
            'year'  => $year,
            'model' => $model,
        ]);
    }
}
