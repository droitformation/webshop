<?php
if(isset($inscription['filter_choices'])){
    unset($inscription['filter_choices']);
}
if(isset($inscription['filter_checkboxes'])){
    unset($inscription['filter_checkboxes']);
}
?>

<tr>
    @foreach($inscription as $col)
        <td>{{ $col }}</td>
    @endforeach
</tr>