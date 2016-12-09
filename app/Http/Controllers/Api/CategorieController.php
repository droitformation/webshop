<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategorieRequest;

use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Service\UploadInterface;

class CategorieController extends Controller {

    protected $categorie;
    protected $upload;

    public function __construct( CategorieInterface $categorie, UploadInterface $upload)
    {
        $this->categorie = $categorie;
        $this->upload    = $upload;
    }
	
    public function index(Request $request)
    {
        $categories = $this->categorie->getAll($request->input('site'));

		return ['categories' => $categories];
    }

}