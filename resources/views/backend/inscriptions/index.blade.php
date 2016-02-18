@extends('backend.layouts.master')
@section('content')
<?php $helper = new \App\Droit\Helper\Helper(); ?>

<div class="row">
    <div class="col-md-4">
        <h3>Dernières inscriptions</h3>
    </div>
    <div class="col-md-8 text-right">
        <a href="{{ url('admin/inscription/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">
            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table normalTable" id="" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-2">Déteteur</th>
                            <th class="col-sm-2">Email</th>
                            <th class="col-sm-1">Groupe et Participant</th>
                            <th class="col-sm-1">No</th>
                            <th class="col-sm-1">Prix</th>
                            <th class="col-sm-2">Status</th>
                            <th class="col-sm-1">Date</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                            @if(!empty($inscriptions))
                                @foreach($inscriptions as $inscription)

                                    <?php $style = ($inscription->group_id ? 'class="isGoupe"' : ''); ?>

                                    <tr {!! $style !!}>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/inscription/'.$inscription->id) }}"><i class="fa fa-edit"></i></a></td>
                                        <td>
                                            <?php
                                                echo '<p><strong>'.($inscription->inscrit && $inscription->adresse_facturation ? $inscription->adresse_facturation->civilite_title : '').'</strong></p>';
                                                echo '<p>'.($inscription->inscrit ? $inscription->inscrit->name : '<span class="label label-warning">Duplicata</span>').'<br/></p>';
                                            ?>
                                        </td>
                                        <td>{!! ($inscription->inscrit ? $inscription->inscrit->email : '<span class="label label-warning">Duplicata</span>') !!}</td>
                                        <td>
                                            @if($inscription->group_id)
                                                {!! $inscription->group_id.'<br/>'.$inscription->participant->name  !!}
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
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-sm deleteAction">X</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@stop