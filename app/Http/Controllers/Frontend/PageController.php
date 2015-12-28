<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Page\Worker\PageWorker;
use App\Droit\Page\Repo\PageInterface;

class PageController extends Controller
{
    protected $page;
    protected $worker;
    protected $helper;

    public function __construct(PageWorker $worker, PageInterface $page)
    {
        $this->page        = $page;
        $this->worker      = $worker;

        $this->helper = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $page       = $this->page->getBySlug($id);
        $parent     = $page->getAncestorsAndSelf()->toHierarchy();

        $template   = $page->template;

        $data['page']   = $page;
        $data['id']     = $id;
        $data['parent'] = $parent;

        return view('frontend.'.$template)->with($data);
    }

}
