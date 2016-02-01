<?php namespace App\Droit\Page\Repo;

use App\Droit\Page\Repo\PageInterface;
use App\Droit\Page\Entities\Page as M;

class PageEloquent implements PageInterface{

    protected $page;

    public function __construct(M $page)
    {
        $this->page = $page;
    }

    public function getAll($site = null){

        return $this->page->site($site)->orderBy('rang')->get();
    }

    public function getTree($key = null, $seperator = '  '){

        return $this->page->getNestedList('title', $key, $seperator);
    }

    public function search($term)
    {
        return $this->page->where('content','LIKE', '%'.$term.'%')->get();
    }

    public function getRoot(){

        return $this->page->where('parent_id','=',0)->orderBy('rang')->get();
    }

    public function find($id){

        return $this->page->with(['contents'])->find($id);
    }

    public function getBySlug($site,$slug)
    {
        return $this->page->with(['contents'])->site($site)->where('slug','=',$slug)->first();
    }

    public function buildTree($data)
    {
        return $this->page->buildTree($data);
    }

    public function create(array $data){

        $page = $this->page->create(array(
            'rang'        => (isset($data['rang']) ? $data['rang'] : ''),
            'title'       => $data['title'],
            'content'     => $data['content'],
            'template'    => $data['template'],
            'site_id'     => $data['site_id'],
            'menu_title'  => $data['menu_title'],
            'slug'        => (isset($data['slug']) && !empty($data['slug']) ? $data['slug'] : \Str::slug($data['menu_title'])),
            'rang'        => (isset($data['rang']) ? $data['rang'] : 0),
            'menu_id'     => (isset($data['menu_id']) ? $data['menu_id'] : null),
            'hidden'      => $data['hidden'] ? 1 : null,
            'created_at'  => date('Y-m-d G:i:s'),
            'updated_at'  => date('Y-m-d G:i:s')
        ));

        if( !$page )
        {
            return false;
        }

        if($data['parent_id'] > 0)
        {
            $parent = $this->page->findOrFail($data['parent_id']);
            $page->makeChildOf($parent);
        }

        return $page;

    }

    public function update(array $data){

        $page = $this->page->findOrFail($data['id']);

        if( ! $page )
        {
            return false;
        }

        $page->fill($data);

        $page->hidden     = $data['hidden'] ? 1 : null;
        $page->updated_at = date('Y-m-d G:i:s');

        $page->save();

        if($data['parent_id'] > 0)
        {
            $parent = $this->page->findOrFail($data['parent_id']);
            $page->makeChildOf($parent);
        }

        return $page;
    }

    public function updateSorting(array $data)
    {
        if(!empty($data))
        {
            foreach($data as $rang => $id)
            {
                $page = $this->find($id);

                if( ! $page )
                {
                    return false;
                }

                $page->rang = $rang;
                $page->save();
            }

            return true;
        }
    }

    public function delete($id){

        $page = $this->page->find($id);

        return $page->delete($id);
    }

}
