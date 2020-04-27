<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Service\UploadInterface;

class AuthorController extends Controller
{
    protected $author;
    protected $upload;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthorInterface $author, UploadInterface $upload)
    {
        $this->author = $author;
        $this->upload = $upload;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $authors = $this->author->getAll();

        return view('backend.authors.index')->with(array('authors' => $authors ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.authors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $all   = $request->all();
        $_file = $request->file('photo', null);

        if($_file)
        {
            $photo = $this->upload->upload($_file, 'files/authors');
            $all['photo'] = $photo['name'];
        }

        $author = $this->author->create($all);

        // Update date new content
        setMaj(\Carbon\Carbon::today()->toDateString(),'hub');

        flash('Auteur crée')->success();

        return redirect('admin/author/'.$author->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $author = $this->author->find($id);

        return view('backend.authors.show')->with(array( 'author' => $author ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $all   = $request->all();
        $_file = $request->file('photo', null);

        if($_file)
        {
            $photo = $this->upload->upload($_file, 'files/authors');
            $all['photo'] = $photo['name'];
        }

        $author = $this->author->update($all);

        // Update date new content
        setMaj(\Carbon\Carbon::today()->toDateString(),'hub');

        flash('Auteur mis à jour')->success();

        return redirect('admin/author/'.$author->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->author->delete($id);

        // Update date new content
        setMaj(\Carbon\Carbon::today()->toDateString(),'hub');

        flash('Auteur supprimé')->success();

        return redirect('admin/author');
    }
}
