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
        $this->data['type'] = isset($this->data['type']) ? $this->data['type'] : 'simple';
        $this->repo_colloque = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->repo_price    = \App::make('App\Droit\Price\Repo\PriceInterface');
    }

    public function multiple($data)
    {
        return collect($data['participant'])->mapWithKeys(function ($participant,$index) use ($data) {

            $colloques = array_unique(array_flatten($data['colloques']));

            return collect($colloques)->mapWithKeys(function ($colloque, $i) use ($data,$index) {

                $participants = collect($data['participant'])->map(function ($participant, $key) use ($data,$colloque) {
                    if(in_array($colloque,$data['colloques'][$key])){

                        $free  = $this->repo_price->getFreeByColloque($key);
                        $price = $colloque != $data['colloque_id'] ? array_filter([
                            'price_id' => $free->id,
                            'price_linked_id' => isPriceLink($data['price_id'][$key])? getPriceId($data['price_id'][$key]) : null,
                        ]) : $this->convertPrices($data['price_id'][$key]);

                        return [
                            'participant' => $data['participant'][$key],
                            'email'    => $data['email'][$key],
                            'options'  => $data['addons'][$colloque]['options'][$key] ? $data['addons'][$colloque]['options'][$key] : null,
                            'groupes'  => $data['addons'][$colloque]['groupes'][$key] ? $data['addons'][$colloque]['groupes'][$key] : null,
                        ] + $price;
                    }
                })->reject(function ($participant, $key) use ($data,$colloque) {
                    return empty($participant);
                })->toArray();

                return [
                    $colloque => array_filter([
                        'user_id'        => $this->data['user_id'],
                        'reference_no'   => $this->data['reference_no'] ?? null,
                        'transaction_no' => $this->data['transaction_no'] ?? null,
                        'rabais_id'      => $colloque == $data['colloque_id'] && isset($this->data['rabais_id']) ? $this->data['rabais_id'] : null,
                        'participants'   => $participants,
                    ])
                ];
            })->toArray();
        });

    }

    public function getColloques($price)
    {
        if(!isPriceLink($price)){
            return [$this->data['colloque_id']];
        }

        return $this->data['colloque_id'];
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

    public function prepare($data)
    {
        $counter = 0;
        $type = $data['type'] ?? 'simple';

        $data['price_linked_id'] = isLinkedPrice($data,$type);

        if(isset($data['addons']) && !empty($data['addons'])){
            return collect($data['addons'])->map(function ($options,$key) use ($data, &$counter) {
                // if original colloque is tthe current
                // It's supports the invoice price
                // Else it's free
                $free  = $this->repo_price->getFreeByColloque($key);
                $price = $this->prices($data,$key,$free);

                // remove rabais for multiple colloques, only de first one is supporting the invoice price
                if($counter > 0){
                    unset($data['rabais_id']);
                }

                $counter++;

                return ['colloque_id' => $key] + $price + array_except(array_filter($data),['addons','_token','price_id']) + $options;
            });
        }

        return collect([
            ['colloque_id' => $data['colloque_id']] + priceConvert($data) + array_except(array_filter($data),['_token','price_id'])
        ]);
    }

    public function prices($data,$key,$free)
    {
        $type = $data['type'] ?? 'simple';

        // price link colloque for multiple
        if($type == 'multiple' && $key != $data['colloque_id']){
            return ['prices' => [['price_id' => $free->id], ['price_id' => $free->id]]];
        }

        if($type == 'simple' && $key != $data['colloque_id']){
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
            'rabais_id'      => $this->data['rabais_id'] ?? null,
            'user_id'        => $this->data['user_id'],
            'colloque_id'    => $this->data['colloque_id'],
            'type'           => $this->data['type'] ?? '',
            'participant'    => $this->data['participant'] ?? null,
            'email'          => $this->data['email'] ?? null,
            'price_id'       => $this->data['price_id'],
            'addons'         => $this->addons()
        ]);
    }

    public function colloquedata($data)
    {
        $type = $this->data['type'] ?? 'simple';

        $options = isset($data['options']) && isset($data['options'][0]) && $type == 'simple' ? $data['options'][0] : ($data['options'] ?? null);
        $groupes = isset($data['groupes']) && isset($data['groupes'][0]) && $type == 'simple' ? $data['groupes'][0] : ($data['groupes'] ?? null);
        $occurrences = $data['occurrences'] ?? null;

        return array_filter([
            'options'     => isset($options) ? array_filter_recursive($options) : null,
            'groupes'     => isset($groupes) ? array_filter_recursive($groupes) : null,
            'occurrences' => isset($occurrences) ? array_filter_recursive($occurrences) : null
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

    public function addons()
    {
        if(isset($this->data['addons'])){
            $colloques = [];
            foreach ($this->data['addons'] as $id => $data){
                $colloques[$id] = $this->colloquedata($data);
            }

            return $colloques;
        }
    }
}