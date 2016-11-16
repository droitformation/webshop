<?php

namespace App\Http\Controllers\Backend\Question;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\QuestionInterface;

class QuestionController extends Controller
{
    protected $question;

    public function __construct(QuestionInterface $question)
    {
        $this->question = $question;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $questions = $this->question->getAll();

        return view('backend.questions.index')->with(['questions' => $questions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $question = $this->question->create($request->all());

        alert()->success('La question a été crée');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $question = $this->question->update($request->all());

        alert()->success('La question a été mis à jour');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->question->delete($id);

        alert()->success('La question a été supprimé');

        return redirect()->back();
    }
}
