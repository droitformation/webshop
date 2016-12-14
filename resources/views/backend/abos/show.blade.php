@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

    <div class="panel panel-midnightblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <img class="thumbnail" style="height: 50px; float:left; margin-right: 15px;padding: 2px;" src="{{ asset('files/main/'.$abo->logo_file) }}" />
                    <h3 style="margin-bottom:0;line-height:24px">Abo</h3>
                    <p style="margin-bottom: 8px;">&Eacute;dition {{ $abo->title }}</p>
                </div>
                <div class="col-md-10">
                    <div class="nav nav-pills">
                        @if(!$abo->products->isEmpty())
                            <?php
                                // Remove product without attributes
                                $products = $abo->products->reject(function ($product, $key) {
                                    $attributs = $product->attributs->pluck('id');
                                    return !$attributs->contains(3) && !$attributs->contains(4);
                                });
                            ?>
                            @foreach($products as $product)
                                <a class="btn btn-default btn-sm" href="{{ url('admin/factures/'.$product->id) }}">
                                    &nbsp;<i class="fa fa-folder-open"></i>&nbsp; &Eacute;dition <strong>{{ $product->reference }} {{ $product->edition }}</strong>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3 class="pull-left">Tous les abonnés</h3>
                    <p class="pull-right">
                        <a href="{{ url('admin/abonnement/create/'.$abo->id) }}" class="btn btn-success btn-sm" id="addAbonne"><i class="fa fa-plus"></i> &nbsp;Ajouter un abonné</a>
                        <a class="btn btn-warning btn-sm" href="{{ url('admin/abo/desinscription/'.$abo->id) }}">Désabonnements</a>
                    </p><div class="clearfix"></div><br/>

                    <table class="table" id="abos-table">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Entreprise</th>
                            <th>Exemplaires</th>
                            <th class="no-sort">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!$abo->abonnements->isEmpty())
                                @foreach($abo->abonnements as $abonnement)
                                    <?php $user = $abonnement->user_adresse; ?>
                                    <tr>
                                        <td><a href="{{ url('admin/abonnement/'.$abonnement->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a></td>
                                        <td>{{ $abonnement->numero }}</td>
                                        <td>{!! $user ? $user->name : '<p><span class="label label-warning">Duplicata</span></p>' !!}</td>
                                        <td>{!! $user ? $user->company : '<p><span class="label label-warning">Duplicata</span></p>' !!}</td>
                                        <td>{{ $abonnement->exemplaires }}</td>
                                        <td>{{ $abonnement->status }}</td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/abonnement/'.$abonnement->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button id="deleteAbo_{{ $abonnement->id }}" type="submit" data-what="Supprimer l'abonné n°" data-action="{{ $abonnement->numero }}" class="btn btn-danger btn-sm deleteAction">Désabonner</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr><th></th><th></th><th></th><th></th><th></th><th>Status</th><th></th></tr>
                        </tfoot>
                    </table>

                </div>
            </div>

        </div>
    </div>

@stop