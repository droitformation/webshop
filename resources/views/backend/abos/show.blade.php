@extends('backend.layouts.master')
@section('content')

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-2">
            <p><a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
        <div class="col-md-6">
            <img class="thumbnail" style="height: 60px; float:left; margin-right: 15px;padding: 2px;" src="{{ asset('files/products/'.$abo->current_product->image) }}" />
            <h3 style="margin-bottom: 0;">Abo</h3>
            <h4 style="margin: 0;">{{ $abo->title }}</h4>
        </div>
        <div class="col-md-2">
            <p class="text-right"><a href="{{ url('admin/abonnement/create/'.$abo->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter un abonné</a></p>
        </div>
        <div class="col-md-2">
            <a class="btn btn-warning btn-block" data-toggle="collapse" href="#desinscriptionTable" aria-expanded="false" aria-controls="desinscriptionTable">Désinscriptions</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">

            <div class="panel panel-midnightblue">
                <div class="panel-body" style="padding-bottom: 0;">
                    <h4 style="margin-top: 0;">Factures</h4>
                    <div class="list-group">
                        @if(!$abo->products->isEmpty())
                            @foreach($abo->products as $product)
                                <a class="list-group-item" href="{{ url('admin/factures/'.$product->id) }}">
                                    &nbsp;<i class="fa fa-folder-open"></i>&nbsp; &Eacute;dition <strong>{{ $product->reference }} {{ $product->edition }}</strong>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10">
            <div class="panel panel-midnightblue">
                <div class="panel-heading"><h4><i class="fa fa-tag"></i> Abonnements</h4></div>
                <div class="panel-body">

                    <table class="table" id="generic">
                        <thead>
                        <tr>
                            <th>Action</th><th>Numéro</th><th>Nom</th><th>Entreprise</th><th>Exemplaires</th><th>Status</th><th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!$abo->abonnements->isEmpty())
                                <?php
                                    $abonnes = $abo->abonnements->reject(function ($abonne) {
                                        return $abonne->status == 'resilie';
                                    });
                                ?>
                                @foreach($abonnes as $abonnement)
                                    <tr>
                                        <td><a href="{{ url('admin/abonnement/'.$abonnement->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                        <td>{{ $abonnement->numero }}</td>
                                        <td>{{ $abonnement->user ? $abonnement->user->name : 'Duplicata' }}</td>
                                        <td>{{ $abonnement->user ? $abonnement->user->company : 'Duplicata'  }}</td>
                                        <td>{{ $abonnement->exemplaires }}</td>
                                        <td>{{ $abonnement->status }}</td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/abonnement/'.$abonnement->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-what="Supprimer l'abonné n°" data-action="{{ $abonnement->numero }}" class="btn btn-danger btn-sm deleteAction">Désabonner</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="collapse" id="desinscriptionTable">

                <div class="panel panel-warning">
                    <div class="panel-body">
                        <h4><i class="fa fa-ban"></i> Résiliations</h4>
                        <table class="table simple-table">
                            <thead>
                            <tr>
                                <th class="col-md-1">Action</th>
                                <th class="col-md-1">Numéro</th>
                                <th class="col-md-4">Nom</th>
                                <th class="col-md-4">Entreprise</th>
                                <th class="col-md-1">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(!$abo->resilie->isEmpty())
                                    @foreach($abo->resilie as $abonnement)
                                        <tr>
                                            <td>
                                                <form action="{{ url('admin/abonnement/restore/'.$abonnement->id) }}" method="POST" class="form-horizontal">
                                                    <input type="hidden" name="_method" value="POST">
                                                    {!! csrf_field() !!}
                                                    <button data-what="Restaurer" data-action="N°: {{ $abonnement->numero }}" class="btn btn-warning btn-xs deleteAction">Restaurer</button>
                                                </form>
                                            </td>
                                            <td>{{ $abonnement->numero }}</td>
                                            <td>{{ $abonnement->user ? $abonnement->user->name : 'Duplicata'  }}</td>
                                            <td>{{ $abonnement->user ? $abonnement->user->company  :'Duplicata' }}</td>
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

    </div>

@stop