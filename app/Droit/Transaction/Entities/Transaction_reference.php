<?php

namespace App\Droit\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;

class Transaction_reference extends Model
{
    protected $table = 'transaction_references';
    protected $fillable = ['reference_no','transaction_no'];

    public $timestamps = false;
}
