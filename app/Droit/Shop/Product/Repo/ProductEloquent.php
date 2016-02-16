<?php namespace App\Droit\Shop\Product\Repo;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Product\Entities\Product as M;

class ProductEloquent implements ProductInterface{

    protected $product;

    public function __construct(M $product)
    {
        $this->product = $product;
    }

    public function getAll($search = null, $nbr = null)
    {
        $products = $this->product->with(array('categories','authors','domains','attributs','orders','abos'))
            ->search($search)
            ->orderBy('created_at', 'DESC');

        if($nbr)
        {
            return $products->paginate($nbr);
        }

        return $products->get();
    }

    public function getNbr($nbr = null, $reject = null)
    {
        return $this->product
            ->with(array('categories','authors','domains','attributs','abos'))
            ->reject($reject)
            ->orderBy('created_at', 'DESC')
            ->paginate($nbr);
    }

    public function getByCategorie($id)
    {
        return $this->product->with(array('authors','attributs','categories'))->whereHas('categories', function($query) use ($id)
        {
            $query->where('categorie_id', '=' ,$id);

        })->orderBy('created_at', 'DESC')->get();
    }

    public function find($id){

        return $this->product->where('id','=',$id)->with(array('categories','authors','domains','attributs','abos'))->get()->first();
    }

    public function getSome($ids){

        return $this->product->whereIn('id', $ids)->get();
    }

    public function create(array $data){

        $product = $this->product->create(array(
            'title'           => $data['title'],
            'teaser'          => $data['teaser'],
            'image'           => $data['image'],
            'description'     => $data['description'],
            'weight'          => $data['weight'],
            'sku'             => $data['sku'],
            'hidden'          => 1,
            'price'           => $data['price'] * 100,
            'is_downloadable' => (isset($data['is_downloadable']) ? $data['is_downloadable'] : null),
            'url'             => (isset($data['url']) ? $data['url'] : null),
            'abo_id'          => (isset($data['abo_id']) ? $data['abo_id'] : null),
            'rang'            => (isset($data['rang']) && $data['rang'] > 0 ? $data['rang'] : 0),
            'created_at'      => date('Y-m-d G:i:s'),
            'updated_at'      => date('Y-m-d G:i:s')
        ));

        if( ! $product )
        {
            return false;
        }

        return $product;

    }

    public function update(array $data){

        $data['hidden'] = (isset($data['hidden']) && $data['hidden'] ? 1 : 0);

        $product = $this->product->findOrFail($data['id']);

        if( ! $product )
        {
            return false;
        }

        $product->fill($data);
        $product->price = $data['price'] * 100;

        $product->save();

        if(isset($data['abo_id']))
        {
            $product->abos()->sync($data['abo_id']);
        }

        return $product;
    }

    public function delete($id){

        return $this->product->find($id)->delete();

    }

}
