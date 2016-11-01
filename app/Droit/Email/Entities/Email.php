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
            $str = $this->body;

            preg_match_all('/<(.*?)>/', $this->to, $match);

            if(isset($match[1]) && isset($match[1][0]))
            {
                $body = $this->extractString($str, 'table');

                return $body;
            }
            
            return $this->body;
        }
    }

    // Function that returns the string between two strings.
    protected function extractString($string, $tag)
    {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput       = true;
        @$doc->loadHTML($string);

        $table = $doc->getElementById('emailBody');

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