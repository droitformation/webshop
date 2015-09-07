<?php $style = ($group ? 'style="background-color:#f7f8fa;"' : ''); ?>
<?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
<tr>
    <td {!! $style !!}><a class="btn btn-sky btn-sm" href="{{ url('admin/page/'.$inscription->id) }}">&Eacute;diter</a></td>
    <td {!! $style !!}>
        <?php
            echo ($group ? '<span class="label label-default">Groupe '.$inscription->group_id.'</span>' : '');
            echo ($inscription->adresse_facturation->company != '' ? '<p><strong>'.$inscription->adresse_facturation->company.'</strong><br/></p>' : '');
            echo '<p>'.$inscription->adresse_facturation->civilite_title.' '.$inscription->adresse_facturation->name.'</p>';
        ?>
    </td>
    <td {!! $style !!}>{{ $inscription->adresse_facturation->email }}</td>
    <td {!! $style !!}><?php echo ($group ? $inscription->participant->name :''); ?></td>
    <td {!! $style !!}><strong>{{ $inscription->inscription_no }}</strong></td>
    <td {!! $style !!}>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
    <td {!! $style !!} class="text-right">
        <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">
            <input type="hidden" name="_method" value="DELETE">
            {!! csrf_field() !!}
            <button data-action="no: {{ $inscription->inscription_no }}" class="btn btn-danger btn-sm deleteAction">Désinscription</button>
        </form>
    </td>
</tr>