<?php
namespace App\Droit\Helper;

class Status
{
    protected $status;
    protected $column;

    public function __construct($status, $column)
    {
        $this->status = $status;
        $this->column = $column;
    }

    public function getColor()
    {
        if($this->column == 'payed_at'){
            return $this->status == 'pending' ? 'warning' : 'success';
        }

        if($this->column == 'send_at'){
            return $this->status == 'pending' ? 'warning' : 'info';
        }
    }

    public function getMessage()
    {
        if($this->column == 'payed_at'){
            return $this->status == 'pending' ? 'En attente' : 'Payé';
        }

        if($this->column == 'send_at'){
            return $this->status == 'pending' ? 'En attente' : 'Envoyé';
        }
    }
}