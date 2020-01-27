<?php
namespace App\Http\Controllers\Backend\Content;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Seminaire\Repo\SeminaireInterface;
use App\Droit\Seminaire\Repo\SubjectInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Service\UploadInterface;

class SeminaireController extends Controller {

    protected $seminaire;
    protected $subject;
    protected $colloque;
	protected $product;
    protected $upload;

    public function __construct(SeminaireInterface $seminaire, SubjectInterface $subject, ProductInterface $product, UploadInterface $upload, ColloqueInterface $colloque)
    {
        $this->seminaire = $seminaire;
        $this->subject   = $subject;
		$this->product   = $product;
        $this->colloque  = $colloque;
        $this->upload    = $upload;

		view()->share('current_site', 2);
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/seminaire
     *
     * @return Response
     */
    public function index()
    {
        $seminaires = $this->seminaire->getAll();

        return view('backend.seminaires.index')->with(['seminaires' => $seminaires]);
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /seminaire/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$products  = $this->product->getAll();
        $colloques = $this->colloque->getAll(false);

        return view('backend.seminaires.create')->with(['products' => $products, 'colloques' => $colloques]);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /seminaire
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $data = $request->except('file');

        if( $request->file('file')){
            $file = $this->upload->upload( $request->file('file') ,  public_path('files/seminaires') , 'product');

            $data['image'] = $file['name'];
        }

        $seminaire = $this->seminaire->create($data);

        event(new \App\Events\ContentUpdated());

        flash('Seminaire crée')->success();

        return redirect('admin/seminaire/'.$seminaire->id);
	}

	/**
	 * Display the specified resource.
	 * GET /seminaire/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $seminaire = $this->seminaire->find($id);
        $products  = $this->product->getAll();
        $colloques = $this->colloque->getAll(false);

        return view('backend.seminaires.show')->with(['seminaire' => $seminaire, 'products' => $products, 'colloques' => $colloques]);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /seminaire/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request)
	{
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        if($_file) {
            $file = $this->upload->upload( $_file , public_path('files/seminaires') , 'product');
            $data['image'] = $file['name'];
        }

        $this->seminaire->update( $data );

        event(new \App\Events\ContentUpdated());

        flash('Seminaire mise à jour')->success();

        return redirect('admin/seminaire/'.$id);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /seminaire/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->seminaire->delete($id);

        flash('Seminaire supprimé')->success();

        return redirect()->back();
	}

}