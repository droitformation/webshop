<?php $style = ($inscription->group_id ? 'class="isGoupe"' : ''); ?>

<tr {!! $style !!}>
    <td>
        <a class="btn btn-sky btn-sm" href="{{ url('admin/inscription/'.$inscription->id) }}"><i class="fa fa-edit"></i></a>
    </td>
    <td>
        <?php
            echo '<p><strong>'.($inscription->inscrit && $inscription->adresse_facturation ? $inscription->adresse_facturation->civilite_title : '').'</strong></p>';
            echo '<p>'.($inscription->inscrit ? $inscription->inscrit->name : '<span class="label label-warning">Duplicata</span>').'<br/></p>';
        ?>
    </td>
    <td>{!! ($inscription->inscrit ? $inscription->inscrit->email : '<span class="label label-warning">Duplicata</span>') !!}</td>
    <td>
        {{ $inscription->group_id ? $inscription->group_id.'<br/>'.$inscription->participant->name   : '' }}
    </td>
    <td><strong>{{ $inscription->inscription_no }}</strong></td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td>

        <p>{{ $inscription->payed_at ? 'payé' : 'en attente' }}</p>

        <input value="{{ $inscription->payed_at ? $inscription->payed_at->format('Y-m-d') : '' }}" class="editablePayementDate datePicker"
               data-name="payed_at"
               data-type="text"
               data-pk="{{ $inscription->id }}"
               data-url="admin/inscription"
               data-title="Date de payment">

    </td>
    <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
    <td class="text-right">
        <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">
            <input type="hidden" name="_method" value="DELETE">
            {!! csrf_field() !!}
            <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-sm deleteAction">X</button>
        </form>
    </td>
</tr>