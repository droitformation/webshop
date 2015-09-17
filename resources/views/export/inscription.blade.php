<?php
echo '<pre>';
//print_r($inscriptions);
echo '</pre>';
?>


<table>
    <tr>
        <td height="80px">
            <h3>{{ $colloque->titre }}</h3>
            <h4>{{ $colloque->soustitre }}</h4>
        </td>
        <td>
            <h4><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->organisateur }}</a></h4>
            <p>{{ $colloque->event_date }}</p>
        </td>
    </tr>
    <tr><td colspan="2"></td></tr>
</table>

@if(!empty($inscriptions))
    @foreach($inscriptions as $idoption =>  $options)

        <table><tr><td>{{ $alloptions[$idoption] }}</td></tr></table>

        @if($type == 'choix')
            @foreach($options as $idgroupe => $option)

                <table><tr><td>{{ $allgroupes[$idgroupe] }}</td></tr></table>

                @include('export.table', ['inscriptions' => $option])
            @endforeach
        @endif

    @endforeach
@endif