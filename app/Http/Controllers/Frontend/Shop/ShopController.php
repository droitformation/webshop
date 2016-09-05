<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Page\Repo\PageInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Site\Repo\SiteInterface;

class ShopController extends Controller {

	protected $colloque;
	protected $product;
	protected $categorie;
	protected $author;
	protected $domain;
    protected $page;
    protected $abo;
    protected $site;
    protected $site_id;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
        ColloqueInterface $colloque,
        ProductInterface $product,
        CategorieInterface $categorie,
        AuthorInterface $author,
        DomainInterface $domain,
        PageInterface $page,
        AboInterface $abo,
        SiteInterface $site
    )
	{
		$this->colloque  = $colloque;
        $this->product   = $product;
		$this->categorie = $categorie;
		$this->domain    = $domain;
        $this->author    = $author;
        $this->page      = $page;
        $this->abo       = $abo;
        $this->site      = $site;

        $this->site_id  = 1;
        $site = $this->site->find(1);

        view()->share('site',$site);

	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $nouveautes = $this->product->getByCategorie(5)->take(6);
		$products   = $this->product->getNbr(10,[5]);
        $colloques  = $this->colloque->getCurrent(true); // $registration = false, $finished = false, $visible = true
        $abos       = $this->abo->getAll();
        
        $abos = $abos->map(function($abo, $key) {
            return $abo->current_product->load('abos');
        });

		return view('frontend.pubdroit.index')->with(['products' => $products, 'abos' => $abos, 'nouveautes' => $nouveautes, 'colloques' => $colloques]);
	}

	public function products(Request $request)
	{
		$search = $request->input('search',null);

		if($search)
		{
			$search = array_filter($search);
		}

		$products = $this->product->getAll($search);

		return view('frontend.pubdroit.index')->with(['products' => $products]);
	}

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        return view('frontend.pubdroit.product')->with(['product' => $product]);
    }

    /**
     *
     * @return Response
     */
    public function categorie($id)
    {
        $products = $this->product->getByCategorie($id);

        return view('frontend.pubdroit.products')->with(['products' => $products, 'title' => 'Catégorie', 'label' => 'Nouveautés']);
    }


    public function sort(Request $request)
    {
        $search = $request->input('search',null);

        if($search)
        {
            $title  = $request->input('title','');
            $label  = $request->input('label',null);

            $label  = $label ? $this->$label->find($search[$label.'_id'])->title : '';

            $products = $this->product->getAll($search);
        }
        else
        {
            return redirect('/');
        }

        return view('frontend.pubdroit.products')->with(['products' => $products, 'title' => $title, 'label' => $label]);
    }

    public function search(Request $request)
    {
        $term = $request->input('term',null);

        $colloques  = $this->colloque->search($term);
        $products   = $this->product->search(trim($term));
        $categories = $this->categorie->search($term);
        $domains    = $this->domain->search($term);
        $authors    = $this->author->search($term);

        return view('frontend.pubdroit.results')->with(['term' => $term, 'products' => $products, 'categories' => $categories, 'domains' => $domains, 'colloques' => $colloques, 'authors' => $authors]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return Response
     */
    public function page($slug,$var = null)
    {
        $page = $this->page->getBySlug($this->site_id,$slug);

        $data['page'] = $page;
        $data['var']  = $var;

        return view('frontend.pubdroit.'.$page->template)->with($data);
    }

    public function subscribe()
    {
        return view('frontend.pubdroit.subscribe');
    }

    public function unsubscribe()
    {
        return view('frontend.pubdroit.unsubscribe');
    }

}
