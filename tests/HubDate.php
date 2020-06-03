<?php namespace Tests;

trait HubDate
{
    public function setDate($what = 'hub')
    {
        $original = \Carbon\Carbon::today()->subDays(3)->toDateString();

        \Storage::disk('local')->put($what.'.txt', $original);

        $this->assertEquals(\Carbon\Carbon::today()->subDays(3)->toDateString(),$original);
    }

    public function isDate($what = 'hub')
    {
        $this->assertEquals(\Carbon\Carbon::today()->toDateString(),getMaj($what));
    }
}