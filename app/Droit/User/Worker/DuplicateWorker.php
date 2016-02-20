<?php

namespace App\Droit\User\Worker;

class DuplicateWorker implements DuplicateWorkerInterface
{
    public function assign($user_id, $data)
    {
        if(isset($data) && !$data->isEmpty())
        {
            foreach($data as $model)
            {
                $model->user_id = $user_id;
                $model->save();
            }
        }
    }
}