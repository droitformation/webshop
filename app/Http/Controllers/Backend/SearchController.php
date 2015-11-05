<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

use App\Http\Requests\SearchRequest;

class SearchController extends Controller
{
    protected $user;
    protected $adresse;

    public function __construct(UserInterface $user, AdresseInterface $adresse)
    {
        $this->user    = $user;
        $this->adresse = $adresse;
        $this->helper  = new \App\Droit\Helper\Helper();
    }

    /**
     *
     * @return Response
     */
    public function form()
    {
        return view('backend.results');
    }

    /**
     * Search user simple
     *
     * @return Response
     */
    public function user(SearchRequest $request)
    {
        $users    = $this->user->searchSimple($request->all());
        $adresses = $this->adresse->searchSimple($request->all());

        return view('backend.results')->with(['users' => $users, 'adresses' => $adresses]);
    }

    /**
     * Search user for inscription autocomplete
     *
     * @return Response
     */
    public function search(Request $request)
    {
        $results = $this->user->search($request->input('term'));
        $results = $this->helper->convertAutocomplete($results);

        return response()->json($results);
    }
}
