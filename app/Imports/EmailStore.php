<?php namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Exportable;

class EmailStore implements ToArray
{
    use Exportable;

    protected $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * @param array $row
     *
     * @return Client|null
     */
    public function array(array $row)
    {
        return $this->list;
    }
}