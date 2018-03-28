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

    public function simple($id)
    {
        $categorie = $this->categorie->find($id);

        return response()->json( [
            'id' => $categorie->id,
            'title' => $categorie->title,
            'image' => $categorie->image,
        ]);
    }

    public function liste($site_id)
    {
        $categories = $this->categorie->getAll($site_id);
        $categories = $categories->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'image' => $item->image,
            ];
        });

        return response()->json($categories);
    }
}