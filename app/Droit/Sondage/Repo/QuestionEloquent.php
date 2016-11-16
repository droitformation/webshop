<?php namespace App\Droit\Sondage\Repo;

use App\Droit\Sondage\Repo\QuestionInterface;
use App\Droit\Sondage\Entities\Question as M;

class QuestionEloquent implements QuestionInterface{

    protected $question;

    public function __construct(M $question)
    {
        $this->question = $question;
    }

    public function getAll()
    {
        return $this->question->all();
    }

    public function find($id)
    {
        return $this->question->find($id);
    }

    public function create(array $data)
    {
        $question = $this->question->create(array(
            'type'     => $data['type'],
            'question' => $data['question'],
            'choices'  => isset($data['choices']) ? $data['choices'] : null
        ));

        if(!$question)
        {
            return false;
        }

        return $question;
    }

    public function update(array $data)
    {
        $question = $this->question->findOrFail($data['id']);

        if(!$question)
        {
            return false;
        }

        $question->fill($data);
        $question->save();

        return $question;
    }

    public function delete($id)
    {
        $question = $this->question->find($id);

        return $question->delete();
    }
}
