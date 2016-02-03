<?php namespace  App\Droit\Faq\Repo;

use  App\Droit\Faq\Repo\FaqQuestionInterface;
use  App\Droit\Faq\Entities\Faq_question as M;

class FaqQuestionEloquent implements FaqQuestionInterface{

    protected $question;

    public function __construct(M $question)
    {
        $this->question = $question;
    }

    public function getAll($site = null,$var = null)
    {
        return $this->question->sites($site)->with(['categories'])->categorie($var)->orderBy('title', 'ASC')->get();
    }

    public function find($id){

        return $this->question->find($id);
    }

    public function create(array $data){

        $question = $this->question->create(array(
            'title'      => $data['title'],
            'site_id'    => (isset($data['site_id']) ? $data['site_id'] : null),
            'question'   => (isset($data['question']) ? $data['question'] : ''),
            'answer'     => (isset($data['answer']) ? $data['answer'] : ''),
            'rang'       => (isset($data['rang']) && $data['rang'] > 1 ? $data['rang'] : 0),
            'created_at' => date('Y-m-d G:i:s'),
            'updated_at' => date('Y-m-d G:i:s')
        ));

        if( ! $question )
        {
            return false;
        }

        // categories
        if(isset($data['categorie_id']))
        {
            $question->categories()->attach($data['categorie_id']);
        }

        return $question;

    }

    public function update(array $data){

        $question = $this->question->findOrFail($data['id']);

        if( ! $question )
        {
            return false;
        }

        $question->fill($data);

        $question->updated_at = date('Y-m-d G:i:s');
        $question->save();

        // categories
        if(isset($data['categorie_id']))
        {
            $question->categories()->sync($data['categorie_id']);
        }

        return $question;
    }

    public function delete($id){

        $question = $this->question->find($id);

        return $question->delete();
    }

}
