<?php  namespace App\Droit\Inscription\Entities;

class Register
{
    protected $data;
    protected $repo_colloque;
    protected $repo_price;
    protected $counter = 0;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->data['type']  = isset($this->data['type']) ? $this->data['type'] : 'simple';
        $this->repo_colloque = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->repo_price    = \App::make('App\Droit\Price\Repo\PriceInterface');
    }

    public function prepare()
    {
        if($this->data['type'] == 'simple'){
            return $this->simple($this->general());
        }

        return $this->multiple();
    }

    public function multiple()
    {
        return collect($this->data['participant'])->mapWithKeys(function ($participant,$index) {

            $colloques = array_unique(array_flatten($this->data['colloques']));

            return collect($colloques)->mapWithKeys(function ($colloque, $i) use ($index) {

                $participants = collect($this->data['participant'])->map(function ($participant, $key) use ($colloque) {

                    if(isset($this->data['colloques']) && in_array($colloque,$this->data['colloques'][$key])){

                        $free  = $this->repo_price->getFreeByColloque($colloque);
                        $price = $colloque != $this->data['colloque_id'] ? array_filter([
                            'price_id' => $free->id,
                            'price_linked_id' => isPriceLink($this->data['price_id'][$key])? getPriceId($this->data['price_id'][$key]) : null,
                        ]) : $this->convertPrices($this->data['price_id'][$key]);

                        return array_filter([
                            'participant' => $this->data['participant'][$key],
                            'email'    => $this->data['email'][$key],
                            'options'  => $this->data['addons'][$colloque]['options'][$key] ?? null,
                            'groupes'  => $this->data['addons'][$colloque]['groupes'][$key] ?? null,
                        ]) + $price;
                    }
                })->reject(function ($participant, $key) use ($colloque) {
                    return empty($participant);
                })->toArray();

                return [
                    $colloque => array_filter([
                        'type'           => 'multiple',
                        'colloque_id'    => $colloque,
                        'user_id'        => $this->data['user_id'],
                        'reference_no'   => $this->data['reference_no'] ?? null,
                        'transaction_no' => $this->data['transaction_no'] ?? null,
                        'rabais_id'      => $colloque == $this->data['colloque_id'] && isset($this->data['rabais_id']) ? $this->data['rabais_id'] : null,
                        'participants'   => $participants,
                    ])
                ];
            })->toArray();
        });

    }

    public function simple()
    {
        $counter = 0;

        $this->data['price_linked_id'] = getLinkId($this->data);

        if(isset($this->data['colloques']) && !empty($this->data['colloques'])){
            $colloques = collect($this->data['colloques'])->map(function ($options,$key) use (&$counter){
                // if original colloque is the current
                // It's supports the invoice price else it's free
                $free  = $this->repo_price->getFreeByColloque($key);
                $price = $this->prices($this->data,$key,$free);

                if($counter > 0){
                    unset($this->data['rabais_id']);
                }

                $counter++;

                return ['colloque_id' => $key] + $price + array_except(array_filter($this->data),['colloques','_token','price_id']) + $options;
            });

            if (isset($this->data['test'])){
                echo '<pre>';
                print_r($colloques);
                echo '</pre>';
                exit;
            }
        }

        return collect([
            $this->data['colloque_id'] => [
                'colloque_id' => $this->data['colloque_id']
            ] + priceConvert($this->data) + array_except(array_filter($this->data),['_token','price_id'])
        ]);
    }

    /*
     * test for type of price
     * */
    public function convertPrices($price)
    {
        return array_filter([
            'price_id'        => !isPriceLink($price) ? getPriceId($price) : null,
            'price_link_id'   => isPriceLink($price)? getPriceId($price) : null,
            'price_linked_id' => isPriceLink($price)? getPriceId($price) : null,
        ]);
    }

    public function prices($data,$key,$free)
    {
        if($key != $data['colloque_id']){
            return ['price_id' => $free->id];
        }

        return priceConvert($data);
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
            'type'           => $this->data['type'],
            'colloque_id'    => $this->data['colloque_id'],
            'price_id'       => $this->data['price_id'],
            'colloques'      => $this->colloques()
        ]);
    }

    public function colloquedata($data)
    {
        return array_filter([
            'options'     => isset($data['options']) ? array_filter_recursive($data['options']) : null,
            'groupes'     => isset($data['groupes']) ? array_filter_recursive($data['groupes']) : null,
            'occurrences' => isset($data['occurrences']) ? array_filter_recursive($data['occurrences']) : null
        ]);
    }

    /*
     * General
     * */
/*    public function getPrice()
    {
        if(is_array($this->data['price_id'])){
            return ['prices' => collect($this->data['price_id'])->map(function ($price, $index) {
                return $price;
            })->toArray()];
        }

        return ['price_id' => $this->data['price_id']];
    }*/

    public function colloques()
    {
        if(isset($this->data['colloques'])){
            $colloques = [];
            foreach ($this->data['colloques'] as $id => $data){
                $colloques[$id] = $this->colloquedata($data);
            }

            return $colloques;
        }
    }
}