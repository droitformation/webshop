<?php

namespace App\Droit\Transaction;

class Preview
{
    protected $colloque;
    protected $data;
    protected $html = '';

    protected $repo_rabais;
    protected $repo_colloque;

    /*
        price_id  "price_link_id:1"

        options[]	"259"  => simple
        groupes[268]	"150" => group choix options
        options[][269]	"sdcfghj"  => text option

        colloque[164][options][]	"259" => simple
        colloque[164][groupes][268]	"150" => group choix options
        colloque[164][options][][269] "qwert" => text option

        reference_no	""
        transaction_no	""
        user_id	"710"
        colloque_id	"164"
      */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($colloque,$data)
    {
        $this->colloque = $colloque;
        $this->data     = $data;

        $this->repo_rabais = \App::make('App\Droit\Inscription\Repo\RabaisInterface');
        $this->repo_colloque = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    }

    public function getHtml()
    {
        $this->html .= $this->price();
        $this->html .= $this->occurrences($this->colloque,array_only($this->data,['occurrences']));
        $this->html .= $this->linkoptions(array_only($this->data,['colloques']));

        $this->html .= isset($this->data['reference_no']) && !empty($this->data['reference_no']) ? '<dl class="ref"><dt>Votre référence</dt><dd><i>'.$this->data['reference_no'].'</i></dd></dl>' : '';
        $this->html .= isset($this->data['transaction_no']) && !empty($this->data['transaction_no']) ? '<dl class="ref"><dt>Votre N° commande</dt><dd><i>'.$this->data['transaction_no'].'</i></dd></dl>' : '';

        return $this->html;
    }

    public function price()
    {
        $price  = priceConvert($this->data['price_id']);
        $relation = $price[0];
        $price_id = $price[1];

        $relation = $relation == 'price_id' ? 'prices' : 'price_link';

        $price  = $this->colloque->$relation->find($price_id);

        $rabais = $this->data['rabais_id'] ?? null;
        $prix   = $price->price_cents;

        $html = '<dl class="link">';

        if($rabais){
            $model = $this->repo_rabais->find($rabais);
            $prix  = $prix - $model->value;

            $html .= '<dt>'.$price->description.'</dt><dd>Prix avec rabais <strong>'.$prix.' CHF</strong></dd>';
            $html .= $rabais ? '<dd class="text-muted" style="margin-top: 5px;">Prix original '.$price->price_cents.' CHF</dd>' : '';
        }
        else{
            $html .= '<dt>'.$price->description.'</dt><dd>Prix '.$prix.' CHF</dd>';
        }

        $html .= '</dl>';

        return $html;
    }

    public function options($colloque,$data)
    {
        $html = '';

        if(isset($data['groupes']) && !empty($data['groupes'])){
             $html .= '<dl>';
                foreach ($data['groupes'] as $option_id => $group_id){
                    $option = getGroup($group_id,$option_id,$colloque);

                    $html .= '<dt>'.$option['title'].'</dt>';
                    $html .= '<dd>'.$option['text'].'</dd>';
                }
            $html .= '</dl>';
        }

        if(isset($data['options']) && !empty($data['options'])){

            $html .= '<dl class="options-wizard"><dt>Choix Options</dt>';
                foreach ($data['options'] as $option_id){
                    $option = getOption($option_id,$colloque);

                    if(isset($option['title'])){
                        $html .= '<dd>'.$option['title'].' '.(isset($option['text']) ? ': '.$option['text'] : '').'</dd>';
                    }
                }
            $html .= '</dl>';
        }

        return $html;
    }

    public function occurrences($colloque,$data)
    {
        $html = '';

        if(isset($data['occurrences']) && !empty($this->data['occurrences'])){
           $html .= '<dl><dt>Choix atelier/lieux</dt>';
               foreach ($data['occurrences']['occurrences'] as $occurrence_id){
                   $occurrence = getOccurrences($occurrence_id,$colloque);
                   $html .= '<dd>'.$occurrence['title'].'</dd>';
               }
           $html .= '</dl>';
        }

        return $html;
    }

    public function linkoptions($data)
    {
        $html = '';

        if(isset($data['colloques']) && !empty($data['colloques'])){
            foreach ($data['colloques'] as $colloque_id => $options){
                $colloque  = $this->repo_colloque->find($colloque_id);
                $html .= '<dl class="link"><dt class="heading"><strong>'.$colloque->titre.'</strong></dt>';
                $html .= $this->options($colloque,$options);
                $html .= '</dl>';
            }
        }

        return $html;
    }

}