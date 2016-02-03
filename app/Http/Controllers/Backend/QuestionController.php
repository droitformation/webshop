<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Faq\Repo\FaqCategorieInterface;
use App\Droit\Faq\Repo\FaqQuestionInterface;

class QuestionController extends Controller
{
    protected $faqcat;
    protected $question;
    protected $site_id;

    public function __construct(FaqCategorieInterface $faqcat, FaqQuestionInterface $question)
    {
        $this->faqcat   = $faqcat;
        $this->question = $question;
        $this->site_id  = 2;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $current    = $this->faqcat->find($id);
        $categories = $this->faqcat->getAll($current->site_id);

        return view('backend.questions.create')->with(['current' => $current, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = $this->question->create( $request->all() );

        return redirect('admin/question/'.$question->id)->with(['status' => 'success' , 'message' => 'Question crée']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question   = $this->question->find($id);
        $categories = $this->faqcat->getAll($question->site_id);

        return view('backend.questions.show')->with(['question' => $question, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = $this->question->update( $request->all() );

        return redirect('admin/question/'.$question->id)->with(['status' => 'success' , 'message' => 'Question mise à jour']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->question->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Question supprimée']);
    }

}
