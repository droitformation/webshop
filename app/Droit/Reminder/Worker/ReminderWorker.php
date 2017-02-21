<?php

namespace App\Droit\Reminder\Worker;

use App\Droit\Reminder\Repo\ReminderInterface;

class ReminderWorker implements ReminderWorkerInterface
{
    protected $reminder;
    protected $helper;

    public function __construct(ReminderInterface $reminder)
    {
        $this->reminder  = $reminder;
        $this->helper    = new \App\Droit\Helper\Helper();
    }

    public function add($attribut, $product, $title, $duration)
    {
        $data['type']     = 'product';
        $data['model']    = 'App\Droit\Shop\Product\Entities\Product';
        $data['model_id'] = $product->id;
        $data['title']    = $title;
        $data['text']     = $attribut->text;
        $data['duration'] = $duration;
        $data['send_at']  = $this->helper->addInterval($product->created_at, $duration);
        $data['start']    = 'created_at';

        return $this->reminder->create( $data );
    }

}