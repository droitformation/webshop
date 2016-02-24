<?php $style = ($inscription->group_id ? 'class="isGoupe"' : ''); ?>

<tr {!! $style !!}>
    <td>
        <a class="btn btn-sky btn-sm" href="{{ url('admin/inscription/'.$inscription->id) }}"><i class="fa fa-edit"></i></a>
    </td>
    <td>
        <?php
            echo '<p><strong>'.($inscription->inscrit && $inscription->adresse_facturation ? $inscription->adresse_facturation->civilite_title : '').'</strong></p>';
            if($inscription->inscrit)
            {
                echo '<p><a class="text-info" href="'.url('admin/user/'.$inscription->inscrit->id).'">'. $inscription->inscrit->name.'</a></p>';
            }
            else
            {
                echo '<p><span class="label label-warning">Duplicata</span></p>';
            }
        ?>
            @if($inscription->group_id)
                <a class="btn btn-info btn-xs" href="admin/inscription/groupe/{{ $inscription->group_id  }}">Changer le détenteur</a>
            @endif
    </td>
    <td>{!! ($inscription->inscrit ? $inscription->inscrit->email : '<span class="label label-warning">Duplicata</span>') !!}</td>
    <td>
        @if($inscription->group_id)
            {!! $inscription->participant->name  !!}
            <a class="btn btn-success btn-xs" href="admin/inscription/add/{{ $inscription->group_id }}">Ajouter un participant</a>
        @endif
    </td>
    <td><strong>{{ $inscription->inscription_no }}</strong></td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td>

        <div class="input-group">
            <div class="form-control editablePayementDate"
                   data-name="payed_at" data-type="date" data-pk="{{ $inscription->id }}"
                   data-url="admin/inscription/edit" data-title="Date de payment">
                {{ $inscription->payed_at ? $inscription->payed_at->format('Y-m-d') : '' }}
            </div>
            <span class="input-group-addon bg-{{ $inscription->payed_at ? 'success' : '' }}">
                {{ $inscription->payed_at ? 'payé' : 'en attente' }}
            </span>
        </div>

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