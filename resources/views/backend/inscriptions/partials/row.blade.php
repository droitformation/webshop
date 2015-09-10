<?php $style = ($group ? 'class="isGoupe"' : ''); setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>

<tr {!! $style !!}>
    <td>{{ $inscription->group_id ? $inscription->group_id  : '' }}</td>
    <td>
        <a class="btn btn-sky btn-xs btn-block" href="{{ url('admin/inscription/'.$inscription->id) }}">&Eacute;diter</a>
        <a class="btn btn-warning btn-xs btn-block" href="{{ url('admin/inscription/generate/'.$inscription->id) }}">Regénérer docs</a>
    </td>
    <td>
        <?php
            echo ($inscription->adresse_facturation->company != '' ? '<p><strong>'.$inscription->adresse_facturation->company.'</strong><br/></p>' : '');
            echo '<p>'.$inscription->adresse_facturation->civilite_title.' '.$inscription->adresse_facturation->name.'</p>';
        ?>
    </td>
    <td>{{ $inscription->adresse_facturation->email }}</td>
    <td><?php echo ($group ? $inscription->participant->name :''); ?></td>
    <td><strong>{{ $inscription->inscription_no }}</strong></td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
    <td class="text-right">
        <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">
            <input type="hidden" name="_method" value="DELETE">
            {!! csrf_field() !!}
            <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-xs deleteAction">Désinscription</button>
        </form>
    </td>
</tr>