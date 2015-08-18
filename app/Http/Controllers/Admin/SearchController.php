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
        $this->user = $user;
    }

    /**
     * Search user
     *
     * @return Response
     */
    public function search(Request $request)
    {
        $results = $this->user->search($request->input('term'));

        return response()->json(array(
            'data' => $results
        ));
    }
}
