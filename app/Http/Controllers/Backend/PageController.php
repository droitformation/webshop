<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Droit\Page\Worker\PageWorker;
use App\Droit\Page\Repo\PageInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Http\Requests\CreatePage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    protected $page;
    protected $worker;
    protected $site;

    public function __construct(PageInterface $page, PageWorker $worker, SiteInterface  $site)
    {
        $this->page   = $page;
        $this->worker = $worker;
        $this->site   = $site;

        view()->share('templates',config('template'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pages = $this->page->getAll();
        $root  = $this->page->getRoot();
        $sites = $this->site->getAll();

        return view('backend.pages.index')->with(array( 'pages' => $pages, 'root' => $root , 'sites' => $sites));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $pages    = $this->page->getTree('id', '&nbsp;&nbsp;&nbsp;');
        $sites    = $this->site->getAll();

        return view('backend.pages.create')->with(['pages' => $pages, 'sites' => $sites]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $page = $this->page->create($request->all());

        return redirect('admin/page/'.$page->id)->with(array('status' => 'success' , 'message' => 'La page a été crée' ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $page  = $this->page->find($id);
        $pages = $this->page->getTree('id', '&nbsp;&nbsp;&nbsp;');
        $sites = $this->site->getAll();

        return view('backend.pages.show')->with(array( 'page' => $page ,'pages' => $pages, 'sites' => $sites));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $page = $this->page->update($request->all());

        return redirect('admin/page/'.$page->id)->with( array('status' => 'success' , 'message' => 'La page a été mise à jour' ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->page->delete($id);

        return redirect('admin/page')->with(array('status' => 'success' , 'message' => 'La page a été supprimé' ));
    }

    public function sorting(Request $request)
    {
        $data = $request->all();

        $pages = $this->page->updateSorting($data['page_rang']);

        print_r($data);
    }

    public function hierarchy(Request $request)
    {
        $data = $request->input('data');

        $tree = $this->worker->prepareTree($data);
       // $new  = $this->page->buildTree($tree);

        echo '<pre>';
        print_r($tree);
        echo '</pre>';

    }
}
