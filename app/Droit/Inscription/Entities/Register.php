<?php  namespace App\Droit\Inscription\Entities;

class Register
{
    protected $data;
    protected $repo_colloque;
    protected $repo_price;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->data['type'] = isset($this->data['type']) ? $this->data['type'] : 'simple';
        $this->repo_colloque = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->repo_price    = \App::make('App\Droit\Price\Repo\PriceInterface');
    }

    public function multiple($data)
    {
        return collect($data['participant'])->map(function ($participant, $index) use ($data) {

            $part['name']  = $data['participant'][$index];
            $part['email'] = $data['email'][$index];
            $part['colloques'] = $this->getColloques($data['price_id'][$index]);
            $part += $this->convertPrices($data['price_id'][$index]);

            return $part;
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

        if(isset($data['colloques']) && !empty($data['colloques'])){
            return collect($data['colloques'])->map(function ($options,$key) use ($data, &$counter) {
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

                return ['colloque_id' => $key] + $price + array_except(array_filter($data),['colloques','_token','price_id']) + $options;
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
            'participant'    => $this->data['participant'] ?? null,
            'email'          => $this->data['email'] ?? null,
            'rabais_id'      => $this->data['rabais_id'] ?? null,
            'user_id'        => $this->data['user_id'],
            'colloque_id'    => $this->data['colloque_id'],
            'type'           => $this->data['type'] ?? '',
            'price_id'       => $this->data['price_id'],
            'colloques'      => $this->colloques()
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