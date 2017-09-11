<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Option\Repo\GroupOptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class OptionGroupeController extends Controller {

    protected $group;
    protected $colloque;

    public function __construct(GroupOptionInterface $group, ColloqueInterface $colloque)
    {
        $this->group = $group;
        $this->colloque = $colloque;
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function store(Request $request)
    {
        $groupe   = $this->group->create($request->all());
        $colloque = $this->colloque->find($request->input('colloque_id'));

        return response()->json(['options' => $colloque->option_display]);
    }
}