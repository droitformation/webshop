<?php $style = ($inscription->group_id ? 'class="isGoupe"' : ''); ?>

<tr {!! $style !!}>
    <td>{{ $inscription->group_id ? $inscription->group_id  : '' }}</td>
    <td>
        <a class="btn btn-sky btn-sm" href="{{ url('admin/inscription/'.$inscription->id) }}">&Eacute;diter</a>
    </td>
    <td>
        <?php
            echo '<p><strong>'.($inscription->inscrit ? $inscription->inscrit->name : '<span class="label label-warning">Duplicata</span>').'</strong><br/></p>';
            echo '<p>'.($inscription->inscrit ? $inscription->inscrit->name : '').'</p>';
        ?>
    </td>
    <td>{!! ($inscription->inscrit ? $inscription->inscrit->email : '<span class="label label-warning">Duplicata</span>') !!}</td>
    <td><?php echo ($inscription->group_id ? $inscription->participant->name :''); ?></td>
    <td><strong>{{ $inscription->inscription_no }}</strong></td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
    <td class="text-right">
        <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">
            <input type="hidden" name="_method" value="DELETE">
            {!! csrf_field() !!}
            <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-sm deleteAction">Désinscription</button>
        </form>
    </td>
</tr>