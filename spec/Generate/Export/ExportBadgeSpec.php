<?php

namespace spec\App\Droit\Generate\Export;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExportBadgeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('App\Droit\Generate\Export\ExportBadge');
    }

    public function it_chuncks_data_for_rows()
    {
        $data = ['Jane Doe','Coralie Jones','Ethan Mallory','Marc Belany'];
        $cols = 2;
        $nbr  = 4;

        $this->chunkData($data,$cols,$nbr)->shouldReturn([
            [
                ['Jane Doe','Coralie Jones'],
                ['Ethan Mallory','Marc Belany']
            ]
        ]);
    }

}
