<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\User\Repo\UserInterface;
use App\Droit\User\Repo\DuplicateInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

use App\Http\Requests\SearchRequest;

class SearchController extends Controller
{
    protected $user;
    protected $duplicate;
    protected $adresse;

    public function __construct(UserInterface $user, AdresseInterface $adresse, DuplicateInterface $duplicate)
    {
        $this->user      = $user;
        $this->duplicate = $duplicate;
        $this->adresse   = $adresse;
        $this->helper    = new \App\Droit\Helper\Helper();
    }

    /**
     *
     * @return Response
     */
    public function form()
    {
        $duplicates = $this->duplicate->getAll();

        return view('backend.results')->with(['duplicates' => $duplicates]);
    }

    /**
     * Search user simple
     *
     * @return Response
     */
    public function user(Request $request)
    {
        $term = $request->input('term',session()->get('term'));
     
        if(!$term) {
            return view('backend.results');
        }

        session(['term' => $term]);

        $adresses = $this->adresse->search($term);

        $results = $adresses->groupBy(function ($adresse, $key) {
            return $adresse->user_id > 0 &&  isset($adresse->user) ? 'users' : 'adresses';
        })->map(function ($items, $key) {
            return $items->map(function ($item, $i) use ($key) {
                return $key == 'users' ? $item->user : $item;
            })->unique('id');
        });

        $users    = isset($results['users']) ? $results['users'] : collect([]);
        $adresses = isset($results['adresses']) ? $results['adresses'] : collect([]);

        return view('backend.results')->with(['users' => $users, 'adresses' => $adresses, 'term' => $term]);
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

    /**
     * Search user for inscription autocomplete
     *
     * @return Response
     */
    public function recherche(Request $request)
    {
        $results = $this->user->search($request->input('term'));
        $results = $this->helper->convertAutocomplete($results);

        return response()->json($results);
    }

    /**
     * Search user for inscription autocomplete
     *
     * @return Response
     */
    public function adresse(Request $request)
    {
        $results = $this->adresse->search($request->input('term'));
        $results = $this->helper->convertAutocomplete($results, 'adresse');

        return response()->json($results);
    }
}
