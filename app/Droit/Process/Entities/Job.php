<?php namespace App\Droit\Process\Entities;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';

    protected $fillable = ['id'];

    public $timestamps = false;
}
