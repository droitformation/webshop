<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user   = $user;
        $this->helper = new \App\Droit\Helper\Helper();
    }

    /**
     * Search user
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
