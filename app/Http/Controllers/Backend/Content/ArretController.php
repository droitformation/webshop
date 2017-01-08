<?php
namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Service\UploadInterface;

class ArretController extends Controller {

    protected $arret;
    protected $categorie;
    protected $upload;
    protected $helper;

    public function __construct( ArretInterface $arret, CategorieInterface $categorie , UploadInterface $upload)
    {
        $this->arret     = $arret;
        $this->categorie = $categorie;
        $this->upload    = $upload;
        $this->helper    = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR');
    }

	/**
	 * Display a listing of the resource.
	 * GET /arret
	 *
	 * @return Response
	 */

    public function index($site)
    {
        $arrets     = $this->arret->getAll($site);
        $categories = $this->categorie->getAll();

        return view('backend.arrets.index')->with(['arrets' => $arrets , 'categories' => $categories, 'current_site' => $site]);
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

        return view('backend.arrets.show')->with([ 'isNewsletter' => true, 'arret' => $arret, 'categories' => $categories, 'current_site' => $arret->site_id]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /categorie/create
     * IsNewsletter for multiselect list scripts
     * @return Response
     */
    public function create($site)
    {
        $categories = $this->categorie->getAll();

        return view('backend.arrets.create')->with(['isNewsletter' => true, 'current_site' => $site, 'categories' => $categories ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data  = $request->except('file');
 
        // Files upload
        if( $request->file('file',null) ) {
            $file = $this->upload->upload( $request->file('file') , 'files/arrets' );
            $data['file'] = $file['name'];
        }

        if($request->input('categories',null)) {
            $data['categories'] = $this->helper->prepareCategories($request->input('categories'));
        }
        
        $arret = $this->arret->create( $data );

        alert()->success('Arrêt crée');

        return redirect('admin/arret/'.$arret->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $data = $request->except('file');

        // Files upload
        if( $request->file('file',null) ) {
            $file = $this->upload->upload( $request->file('file') , 'files/arrets' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));
        
        $arret = $this->arret->update( $data );
    
        alert()->success('Arrêt mis à jour');

        return redirect('admin/arret/'.$arret->id);
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

        alert()->success('Arrêt supprimée');

        return redirect()->back();
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