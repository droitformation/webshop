<?php

namespace App\Http\Controllers\Backend;

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

    public function __construct(ReminderInterface $reminder, ProductInterface $product, ColloqueInterface $colloque, AttributeInterface $attribute)
    {
        $this->reminder  = $reminder;
        $this->product   = $product;
        $this->attribute = $attribute;
        $this->colloque  = $colloque;
    }

    public function index()
    {
        $reminders = $this->reminder->getAll();

        return view('backend.reminders.index')->with(['reminders' => $reminders]);
    }

    public function create($type)
    {
        $active = ($type == 'colloque' ? true : null); // products getall pass search
        $items  = $this->$type->getAll($active);

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
        $id    = $request->input('model_id');
        $date  = $request->input('send_at');
        $type  = $request->input('type');
        $model = $this->$type->find($id);

        $send_at  = $model->$date;

        $data = $request->all();
        $data['send_at'] = $send_at;

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
        $type   = $reminder->type;
        $active = ($type == 'colloque' ? true : null); // products getall pass search
        $items  = $this->$type->getAll($active);

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
        $reminder = $this->reminder->update( $request->all() );

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
