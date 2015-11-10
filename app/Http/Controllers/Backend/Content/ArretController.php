<?php
namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Site\Repo\SiteInterface;

class ArretController extends Controller {

    protected $arret;
    protected $categorie;
    protected $upload;
    protected $helper;
    protected $site;

    public function __construct( ArretInterface $arret, CategorieInterface $categorie , UploadInterface $upload, SiteInterface $site)
    {
        $this->arret     = $arret;
        $this->categorie = $categorie;
        $this->upload    = $upload;
        $this->site      = $site;
        $this->helper    = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR');
    }

	/**
	 * Display a listing of the resource.
	 * GET /arret
	 *
	 * @return Response
	 */

    public function index()
    {
        $arrets     = $this->arret->getAll();
        $categories = $this->categorie->getAll();
        $sites      = $this->site->getAll();

        return view('backend.arrets.index')->with(['arrets' => $arrets , 'categories' => $categories, 'sites' => $sites]);
    }

    /**
     * Return one arret by id
     *
     * @return json
     */
    public function show($id)
    {
        $arret      = $this->arret->find($id);
        $categories = $this->categorie->getAll();
        $sites      = $this->site->getAll();

        return view('backend.arrets.show')->with([ 'isNewsletter' => true, 'arret' => $arret, 'categories' => $categories, 'sites' => $sites]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->categorie->getAll();
        $sites      = $this->site->getAll();

        return view('backend.arrets.create')->with([ 'isNewsletter' => true, 'categories' => $categories, 'sites' => $sites]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        // Files upload
        if( $_file )
        {
            $file = $this->upload->upload( $request->file('file') , 'files/arrets' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));

        // Create arret
        $arret = $this->arret->create( $data );

        return redirect('admin/arret/'.$arret->id)->with( array('status' => 'success' , 'message' => 'Arrêt crée') );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        // Files upload
        if( $_file )
        {
            $file = $this->upload->upload( $request->file('file') , 'files/arrets' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));

        // Create arret
        $arret = $this->arret->update( $data );

        return redirect('admin/arret/'.$arret->id)->with( array('status' => 'success' , 'message' => 'Arrêt mis à jour') );

    }

    /**
     * Remove the specified resource from storage.
     * DELETE /adminconotroller/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->arret->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Arrêt supprimée' ));
    }


    /**
     * Return response arrets
     *
     * @return response
     */
    public function arrets($site = null)
    {
        return response()->json( $this->arret->getAll($site) , 200 );
    }

    /**
     * Return one arret by id
     *
     * @return json
     */
    public function simple($id)
    {
        return $this->arret->find($id);
    }

}