<?php
/*echo '<pre>';
print_r($dispatch);
echo '</pre>';*/
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
    @foreach($inscriptions as $groupes)
        @include('export.table', ['inscriptions' => $groupes])
    @endforeach
@endif