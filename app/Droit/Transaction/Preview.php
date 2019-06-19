<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 2019-06-19
 * Time: 13:15
 */

namespace App\Droit\Transaction;

class Preview
{
    protected $colloque;
    protected $data;
    protected $html = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($colloque,$data)
    {
        $this->colloque = $colloque;
        $this->data = $data;
    }

    public function price()
    {
        $price = $this->colloque->prices->find($this->data['price_id']);
        
        $this->html .= '<strong>Prix</strong>: '.$price->description.' | '.$price->price_cents;
    }

    public function options()
    {
        if(isset($this->data['groupes']) && !empty($this->data['groupes'])){
            foreach ($this->data['groupes'] as $options_id => $group_id){
                $option = $this->colloque->options->find($options_id);
                $groupe = $option->groupe->find($group_id);
                $this->html .= '<strong>'.$option->title.'</strong>';
                $this->html .= '<span>'.$groupe->description.'</span>';
            }
        }

        if(isset($this->data['options']) && !empty($this->data['options'])){
            foreach ($this->data['options'] as $options_id){
                $option = $this->colloque->options->find($options_id);
                $this->html .= '<strong>'.$option->title.'</strong>';
            }
        }

    }

    public function occurrences()
    {
        
    }

}