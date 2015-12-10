<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboRappelController extends Controller {

    protected $rappel;
    protected $product;

    public function __construct(AboRappelInterface $rappel, ProductInterface $product)
    {
        $this->rappel  = $rappel;
        $this->product = $product;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	public function store(Request $request)
	{
        $rappel = $this->rappel->create($request->all());

        return redirect()->back()->with(array('status' => 'success', 'message' => 'La rappel a été crée' ));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rappel = $this->rappel->update($request->all());

        return redirect('admin/abo/'.$rappel->id)->with(array('status' => 'success', 'message' => 'La rappel a été mis à jour' ));
    }
		
	public function destroy(Request $request)
	{

	}

}