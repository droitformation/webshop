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
}