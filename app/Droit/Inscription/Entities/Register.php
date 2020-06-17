<?php  namespace App\Droit\Inscription\Entities;

class Register
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /*
     *  Frontend helper
     * */
    public function prepare($data)
    {

        if(isset($data['colloques']) && !empty($data['colloques'])){
            return collect($data['colloques'])->map(function ($options,$key) use ($data) {
                return ['colloque_id' => $key] + priceConvert($data) + array_except($data,['colloques','_token','price_id']) + $options;
            });
        }

        return collect([
            ['colloque_id' => $data['colloque_id']] + priceConvert($data) + array_except($data,['_token','price_id'])
        ]);
    }

    /*
     *  Backend helper
     * */
    public function general()
    {
        return array_filter([
            'reference_no'   => $this->data['reference_no'] ?? null,
            'transaction_no' => $this->data['transaction_no'] ?? null,
            'participant'    => $this->data['participant'] ?? null,
            'email'          => $this->data['email'] ?? null,
            'rabais_id'      => $this->data['rabais_id'] ?? null,
            'user_id'        => $this->data['user_id'],
            'colloque_id'    => $this->data['colloque_id'],
            'type'           => $this->data['type'],
            'price_id'       => $this->data['price_id'],
            'colloques'      => $this->colloques()
        ]);
    }

    public function colloquedata($data,$id)
    {
        return array_filter([
            'options'     => isset($data['options']) && isset($data['options'][0]) && $this->data['type'] == 'simple' ? $data['options'][0] : ($data['options'] ?? null),
            'groupes'     => $data['groupes'] ?? null,
            'occurrences' => $data['occurrences'] ?? null,
        ]);
    }

    /*
     * General
     * */
    public function getPrice()
    {
        if(is_array($this->data['price_id'])){
            return ['prices' => collect($this->data['price_id'])->map(function ($price, $index) {
                return $price;
            })->toArray()];
        }

        return ['price_id' => $this->data['price_id']];
    }

    public function colloques()
    {
        if(isset($this->data['colloques'])){
            $colloques = [];
            foreach ($this->data['colloques'] as $id => $data){
                $colloques[$id] = $this->colloquedata($data,$id);
            }

            return $colloques;
        }
    }

}