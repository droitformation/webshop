<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;
use App\Droit\Page\Worker\PageWorker;
use App\Droit\Page\Repo\PageInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Menu\Repo\MenuInterface;
use App\Http\Requests\PageCreateRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    protected $page;
    protected $worker;
    protected $site;
    protected $menu;

    public function __construct(PageInterface $page, PageWorker $worker, SiteInterface  $site, MenuInterface $menu)
    {
        $this->page   = $page;
        $this->worker = $worker;
        $this->site   = $site;
        $this->menu   = $menu;

        view()->share('templates',config('template'));
        view()->share('menus',$this->menu->getAll()->pluck('title','id')->toArray());
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($site)
    {
        $pages = $this->page->getAll($site);
        $root  = $this->page->getRoot($site);

        return view('backend.pages.index')->with(['pages' => $pages, 'root' => $root, 'current_site' => $site]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($site)
    {
        $menus = $this->menu->getAll($site);

        return view('backend.pages.create')->with(['menus' => $menus, 'current_site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PageCreateRequest $request)
    {
        $page = $this->page->create($request->all());

        flash('La page a été crée')->success();

        return redirect('admin/page/'.$page->id);
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
        $menus = $this->menu->getAll();
        $site  = $this->site->find(2);
        
        $types = config('columns.types');

        return view('backend.pages.show')
            ->with(['page' => $page, 'menus' => $menus, 'current_site' => $page->site_id, 'categories' => $site->categories, 'types' => $types]);
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

        flash('La page a été mise à jour')->success();

        return redirect('admin/page/'.$page->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $page  = $this->page->find($id);

        $this->page->delete($id);

        flash('La page a été supprimé')->success();

        return redirect()->back();
    }

    public function sorting(Request $request)
    {
        $data = $request->all();

        $pages = $this->page->updateSorting($data['page_rang']);
        
        echo 'ok';die();
    }

    public function hierarchy(Request $request)
    {
        $data = $request->input('data');

        $tree = $this->worker->prepareTree($data);
       // $new  = $this->page->buildTree($tree);

        echo 'ok';die();

    }
}
