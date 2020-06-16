<?php  namespace App\Droit\Inscription\Entities;

class Register
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function general()
    {
        return array_filter([
            'reference_no'   => $this->data['reference_no'] ?? null,
            'transaction_no' => $this->data['transaction_no'] ?? null,
            'participant'    => $this->data['participant'] ?? null,
            'occurrences'    => $this->data['occurrences'] ?? null,
            'email'          => $this->data['email'] ?? null,
            'rabais_id'      => $this->data['rabais_id'] ?? null,
            'user_id'        => $this->data['user_id'],
            'colloque_id'    => $this->data['colloque_id'],
            'type'           => $this->data['type'],
            'options'        => $this->data['options'] ?? null,
            'colloques'      => $this->colloques()
        ]) + $this->getPrice();
    }

    public function getPrice()
    {
        if(is_array($this->data['price_id'])){
            $prices = [];
            foreach ($this->data['price_id'] as $index => $price){
                $price = explode(':',$price);
                $prices[$index] = [$price[0] => $price[1]];
            }

            return ['prices' => $prices];
        }

        $price = explode(':',$this->data['price_id']);

        return [$price[0] => $price[1]];
    }

    public function colloques()
    {
        if(isset($this->data['colloque'])){
            $colloques = [];
            foreach ($this->data['colloque'] as $id => $options){
                $colloques[$id] = $this->colloqueoptions($options,$id);
            }

            return $colloques;
        }
    }

    public function colloqueoptions($options,$id)
    {
        return [
            'options' =>  isset($options['options']) && isset($options['options'][0]) && $this->data['type'] == 'simple' ? $options['options'][0] : ($options['options'] ?? null),
            'groupes' =>  $options['groupes'] ?? null,
        ];
    }
}