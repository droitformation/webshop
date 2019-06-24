<?php namespace App\Droit\Transaction;

class Reference
{
    /**
     * @param $model
     * @return App\Droit\Transaction\Entities\Transaction_reference | null
     */
    static function make($model)
    {
        $references = [];
        // if we have references
        $references['reference_no']   = session()->get('reference_no');
        $references['transaction_no'] = session()->get('transaction_no');

        if(!empty(array_filter($references))){
            $reference = \App\Droit\Transaction\Entities\Transaction_reference::create(array_filter($references));

            $model->reference_id = $reference->id;
            $model->save();

            return $reference;
        }

        return null;
    }

    static function update($model,$data){

        // There are references to create or update
        if(!empty(array_filter($data))){
            if(isset($model->references)){
                $reference = $model->references;
                $reference->reference_no   = isset($data['reference_no']) ? $data['reference_no'] : null;
                $reference->transaction_no = isset($data['transaction_no']) ? $data['transaction_no'] : null;
                $reference->save();
            }
            else{
                $reference = \App\Droit\Transaction\Entities\Transaction_reference::create(array_filter($data));

                $model->reference_id = $reference->id;
                $model->save();
            }

            return $reference;
        }

        return isset($model->references) ? $model->references->delete() : null;
    }
}