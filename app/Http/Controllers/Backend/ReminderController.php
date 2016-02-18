<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Reminder\Repo\ReminderInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class ReminderController extends Controller
{
    protected $reminder;
    protected $product;
    protected $attribute;
    protected $colloque;
    protected $helper;
    protected $types;

    public function __construct(ReminderInterface $reminder, ProductInterface $product, ColloqueInterface $colloque, AttributeInterface $attribute)
    {
        $this->reminder  = $reminder;
        $this->product   = $product;
        $this->attribute = $attribute;
        $this->colloque  = $colloque;
        $this->types     = array_keys(config('jobs'));

        $this->helper  = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index()
    {
        $reminders = $this->reminder->getAll();
        $trashed   = $this->reminder->trashed();

        return view('backend.reminders.index')->with(['reminders' => $reminders, 'trashed' => $trashed]);
    }

    public function create($type)
    {
        if($type != 'rappel')
        {
            $active = ($type == 'colloque' ? true : null); // products getall pass search
            $items  = $this->$type->getAll($active);
        }
        else
        {
            $items = null;
        }

        return view('backend.reminders.create')->with(['type' => $type, 'items' => $items]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /reminder
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $id       = $request->input('model_id',null);
        $data     = $request->all();
        $start    = $request->input('send_at');

        if($id)
        {
            $type      = $request->input('type');

            $model     = $this->$type->find($id);
            $send_at   = $model->$start;

            $data['send_at'] = $this->helper->addInterval($send_at, $request->input('interval'));
        }
        else
        {
            $data['send_at'] = $this->helper->addInterval(Carbon::now(), $request->input('interval'));
        }

        $data['start'] = $start;

        $reminder = $this->reminder->create( $data );

        return redirect('admin/reminder/'.$reminder->id)->with(['status' => 'success' , 'message' => 'Rappel crée']);
    }

    /**
     * Display the specified resource.
     * GET /reminder/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $reminder  = $this->reminder->find($id);
        $type      = $reminder->type;

        if($type != 'rappel')
        {
            $active = ($type == 'colloque' ? true : null); // products getall  search
            $items  = $this->$type->getAll($active);
        }
        else
        {
            $items = null;
        }

        return view('backend.reminders.show')->with(['reminder' => $reminder, 'items' => $items]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /reminder/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $id       = $request->input('model_id',null);
        $data     = $request->all();
        $start    = $request->input('send_at');

        if($id)
        {
            $type      = $request->input('type');

            $model     = $this->$type->find($id);
            $send_at   = $model->$start;

            $data['send_at'] = $this->helper->addInterval($send_at, $request->input('interval'));
        }
        else
        {
            $data['send_at'] = $this->helper->addInterval(Carbon::now(), $request->input('interval'));
        }

        $data['start'] = $start;

        $reminder = $this->reminder->update( $data );

        return redirect('admin/reminder/'.$reminder->id)->with(['status' => 'success' , 'message' => 'Rappel mis à jour']);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /reminder
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->reminder->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Rappel supprimé']);
    }

}
