@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-warning">
                <div class="panel-body">

                    <img class="thumbnail" style="height: 50px; float:left; margin-right: 15px;padding: 2px;" src="{{ secure_asset('files/products/'.$abo->current_product->image) }}" />
                    <h3 style="margin-bottom:0;line-height:24px">Abo</h3>
                    <p style="margin-bottom: 15px;">&Eacute;dition {{ $abo->title }}</p>
                    <h3 style="margin-bottom: 20px;">Résiliations</h3>

                    <table class="table simple-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Action</th>
                            <th class="col-md-1">Numéro</th>
                            <th class="col-md-2">Nom</th>
                            <th class="col-md-3">Entreprise</th>
                            <th class="col-md-1">Date résiliation</th>
                            <th class="col-md-3">Raison résiliation</th>
                            <th class="col-md-1">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$abo->resilie->isEmpty())
                            @foreach($abo->resilie->sortBy('numero') as $abonnement)
                                <?php $user = $abonnement->user_adresse; ?>
                                <tr>
                                    <td>
                                        <form action="{{ url('admin/abonnement/restore/'.$abonnement->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                                            <button id="restore_{{ $abonnement->id }}" data-what="Restaurer" data-action="N°: {{ $abonnement->numero }}" class="btn btn-warning btn-xs deleteAction">Restaurer</button>
                                        </form>
                                    </td>
                                    <td>{{ $abonnement->numero }}</td>
                                    <td>
                                        {!! $user ? $user->name : '<p><span class="label label-warning">Duplicata</span></p>' !!}

                                        @if($abonnement->tiers_user_id || $abonnement->tiers_id)
                                            <p><strong>Tiers payant:</strong></p>
                                            {{ $abonnement->user_facturation->name }}<br/>
                                            {!! $abonnement->user_facturation->adresse.'<br/>'.$abonnement->user_facturation->npa.' '.$abonnement->user_facturation->ville !!}
                                        @endif
                                    </td>
                                    <td>{!! $user && ($user->company != $user->name) ? $user->company  : '' !!}</td>
                                    <td>{{ $abonnement->deleted_at ? $abonnement->deleted_at->format('d/m/Y') : 'pas de date' }}</td>
                                    <td>{!! $abonnement->raison !!}</td>
                                    <td>Résilié</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@stop