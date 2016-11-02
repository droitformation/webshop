<?php namespace App\Droit\Email\Entities;

use Illuminate\Database\Eloquent\Model;

class Email extends Model{

    protected $table = 'email_log';
    
    protected $dates = ['date'];

    protected $fillable = ['to','date','subject','body'];

    public function getBodyCleanAttribute()
    {
        if($this->body)
        {
            $body = $this->extractString($this->body);

            return $body ? $body : $this->body;
        }
    }

    // Function that returns the string between two strings.
    protected function extractString($string)
    {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput       = true;
        @$doc->loadHTML($string);

        $table = $doc->getElementById('mainBody');

        return $table->c14n();
    }

    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function scopePeriod($query, $period)
    {
        if (!empty($period['start']) && !empty($period['end'])) $query->whereBetween('date', [$period['start'], $period['end']]);
    }
}