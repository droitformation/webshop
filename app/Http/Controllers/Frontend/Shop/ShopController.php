<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Page\Repo\PageInterface;
use App\Droit\Site\Repo\SiteInterface;

class ShopController extends Controller {

	protected $colloque;
	protected $product;
	protected $categorie;
	protected $author;
	protected $domain;
    protected $page;
    protected $site;
    protected $site_id;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ColloqueInterface $colloque,ProductInterface $product,CategorieInterface $categorie, AuthorInterface $author, DomainInterface $domain, PageInterface $page, SiteInterface $site)
	{
		$this->colloque  = $colloque;
        $this->product   = $product;
		$this->categorie = $categorie;
		$this->author    = $author;
		$this->domain    = $domain;
        $this->page      = $page;
        $this->site      = $site;

        $this->site_id  = 1;
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
        $colloques  = $this->colloque->getAll(true);

		return view('frontend.pubdroit.index')->with(['products' => $products, 'nouveautes' => $nouveautes, 'colloques' => $colloques]);
	}

	public function products(Request $request)
	{
		$search = $request->input('search',null);

		if($search)
		{
			$search = array_filter($search);
		}

		$products    = $this->product->getAll($search);
		return view('frontend.pubdroit.index')->with(['products' => $products]);
	}

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        return view('shop.show')->with(['product' => $product]);
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
/*
        if($slug == 'newsletter')
        {
            if($var)
            {
                $data['campagne'] = $this->campagne->find($var);
                $data['content']  = $this->worker->prepareCampagne($var);
            }
            else
            {
                $newsletters = $this->newsletter->getAll($this->site_id)->first();
                if(!$newsletters->campagnes->isEmpty())
                {
                    $data['campagne'] = $newsletters->campagnes->first();
                    $data['content']  = $this->worker->prepareCampagne($newsletters->campagnes->first()->id);
                }
            }

            $data['categories']    = $this->worker->getCategoriesArrets();
            $data['imgcategories'] = $this->worker->getCategoriesImagesArrets();
        }*/

        return view('frontend.pubdroit.'.$page->template)->with($data);
    }


    /**
     * Send contact message
     *
     * @return Response
     */
    public function sendMessage(SendMessage $request){

        $data = array('email' => $request->email, 'name' => $request->name, 'remarque' => $request->remarque );

        Mail::send('emails.contact', $data, function ($message) use ($data) {

            $message->from($data['email'], $data['name']);
            $message->to('secretariat.droit@unine.ch')->subject('Message depuis le site www.publications-droit.ch');
        });

        return redirect('/')->with(array('status' => 'success', 'message' => '<strong>Merci pour votre message</strong><br/>Nous vous contacterons dès que possible.'));

    }
}
