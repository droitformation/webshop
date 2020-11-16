<?php namespace App\Droit\Shop\Product\Worker;

use App\Droit\Shop\Product\Entities\Product;

class ProductValidation
{
    protected $product;
    
    public $errors = [];

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function activate()
    {
        $this->hasAttributes();

        if(!empty($this->errors)){
            flash(implode(', ', $this->errors))->warning();
            return redirect()->back();
        }

        return true;
    }

    public function hasAttributes()
    {
        if(empty($this->product->reference) || empty($this->product->edition))
        {
            $this->errors[] = 'Le livre doit avoir une référence ainsi que l\'édition comme attributs pour devenir un abonnement';
        }

        return $this;
    }
}