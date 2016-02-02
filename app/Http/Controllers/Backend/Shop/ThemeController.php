<?php
namespace App\Http\Controllers\Backend\Shop;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ThemeRequest;

use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Service\UploadInterface;

class ThemeController extends Controller {

    protected $theme;
    protected $upload;
    protected $site;

    public function __construct( CategorieInterface $theme, UploadInterface $upload, SiteInterface  $site)
    {
        $this->theme  = $theme;
        $this->upload = $upload;
        $this->site   = $site;
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/theme/create
     *
     * @return Response
     */
    public function index()
    {
        $themes  = $this->theme->getAll();
		$parents = $this->theme->getParents();
        $sites   = $this->site->getAll();

        return view('backend.themes.index')->with(['themes' => $themes, 'parents' => $parents, 'sites' => $sites]);
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /theme/create
	 *
	 * @return Response
	 */
	public function create()
	{
        $sites = $this->site->getAll();

        return view('backend.themes.create')->with(['sites' => $sites]);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /theme
	 *
	 * @return Response
	 */
	public function store(ThemeRequest $request)
	{
        $data =  $request->except('file');
		$_file = $request->file('file',null);

		if($_file)
		{
			$file = $this->upload->upload( $request->file('file') , 'files/upload' , 'categorie');

			$data['image'] = $file['name'];
		}

        $theme = $this->theme->create( $data );

        return redirect('admin/theme/'.$theme->id)->with(['status' => 'success' , 'message' => 'Thème crée']);
	}

	/**
	 * Display the specified resource.
	 * GET /theme/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $theme = $this->theme->find($id);
        $sites = $this->site->getAll();

        return view('backend.themes.show')->with(['theme' => $theme, 'sites' => $sites]);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /theme/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request)
	{
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        if($_file)
        {
            $file = $this->upload->upload( $_file , 'files/uploads' , 'categorie');
            $data['image'] = $file['name'];
        }

        $this->theme->update( $data );

        return redirect('admin/theme/'.$id)->with(['status' => 'success' , 'message' => 'Thème mise à jour']);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /theme/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->theme->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Thème supprimée']);
	}

}