<?php namespace App\Droit\Reminder\Repo;

use App\Droit\Reminder\Repo\ReminderInterface;
use App\Droit\Reminder\Entities\Reminder as M;

use Carbon\Carbon;

class ReminderEloquent implements ReminderInterface{

    protected $reminder;

    public function __construct(M $reminder)
    {
        $this->reminder = $reminder;
    }

    public function getAll()
    {
        return $this->reminder->orderBy('send_at')->paginate(20);
    }

    public function trashed()
    {
        return $this->reminder->onlyTrashed()->orderBy('send_at')->paginate(20);
    }

    public function toSend()
    {
        return $this->reminder->where('send_at','<=', Carbon::now()->format('Y-m-d'))->get();
    }

    public function find($id){

        return $this->reminder->find($id);
    }

    public function create(array $data){

        $reminder = $this->reminder->create(array(
            'send_at'     => $data['send_at'],
            'title'       => $data['title'],
            'type'        => $data['type'],
            'start'       => $data['start'],
            'duration'    => $data['duration'],
            'text'        => (isset($data['text']) ? $data['text'] : null),
            'model_id'    => (isset($data['model_id']) ? $data['model_id'] : null),
            'model'       => (isset($data['model']) ? $data['model'] : null),
            'relation'    => (isset($data['relation']) ? $data['relation'] : null),
            'relation_id' => (isset($data['relation_id']) ? $data['relation_id'] : null),
            'created_at'  => date('Y-m-d G:i:s'),
            'updated_at'  => date('Y-m-d G:i:s')
        ));

        if( ! $reminder )
        {
            return false;
        }

        return $reminder;

    }

    public function update(array $data){

        $reminder = $this->reminder->findOrFail($data['id']);

        if( ! $reminder )
        {
            return false;
        }

        $reminder->fill($data);

        $reminder->save();

        return $reminder;
    }

    public function delete($id){

        $reminder = $this->reminder->find($id);

        return $reminder->delete();

    }
}
