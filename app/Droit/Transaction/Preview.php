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

    public function getHtml()
    {
        $this->price()->options()->occurrences();

        $this->html .= isset($this->data['reference_no']) && !empty($this->data['reference_no']) ? '<dl class="ref"><dt>Votre référence</dt><dd><i>'.$this->data['reference_no'].'</i></dd></dl>' : '';
        $this->html .= isset($this->data['transaction_no']) && !empty($this->data['transaction_no']) ? '<dl class="ref"><dt>Votre N° commande</dt><dd><i>'.$this->data['transaction_no'].'</i></dd></dl>' : '';

        return $this->html;
    }

    public function price()
    {
        $price = $this->colloque->prices->find($this->data['price_id']);
        
        $this->html .= '<dl><dt>Prix</dt><dd>'.$price->price_cents.' CHF  - '.$price->description.'</dd></dl>';

        return $this;
    }

    public function options()
    {
        if(isset($this->data['groupes']) && !empty($this->data['groupes'])){
            $this->html .= '<dl>';
                foreach ($this->data['groupes'] as $option_id => $group_id){
                    $option = $this->colloque->options->find($option_id);
                    $option = $option->load('groupe');
                    $groupe = $option->groupe->find($group_id);

                    $this->html .= '<dt>'.$option->title.'</dt>';
                    $this->html .= '<dd>'.$groupe->text.'</dd>';
                }
            $this->html .= '</dl>';
        }

        if(isset($this->data['options']) && !empty($this->data['options'])){
            $this->html .= '<dl><dt>Choix Options</dt>';
                foreach ($this->data['options'] as $option_id){
                    $option = $this->colloque->options->find($option_id);
                    $this->html .= '<dd>'.$option->title.'</dd>';
                }
            $this->html .= '</dl>';
        }

        return $this;
    }

    public function occurrences()
    {
       if(isset($this->data['occurrences']) && !empty($this->data['occurrences'])){
           $this->html .= '<dl><dt>Choix atelier/lieux</dt>';
               foreach ($this->data['occurrences'] as $occurrence_id){
                   $occurrence = $this->colloque->occurrences->find($occurrence_id);
                   $this->html .= '<dd>'.$occurrence->title.'</dd>';
               }
           $this->html .= '</dl>';
       }

        return $this;
    }

}