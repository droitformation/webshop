@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

    <h2>Abonnés</h2>
    <div class="panel panel-midnightblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <img class="thumbnail" style="height: 50px; float:left; margin-right: 15px;padding: 2px;" src="{{ secure_asset('files/main/'.$abo->logo_file) }}" />
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
                                    &nbsp;<i class="fa fa-folder-open"></i>&nbsp; Liste factures <strong>{{ $product->reference }} {{ $product->edition }}</strong>
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

                    @include('backend.abos.partials.options')

                    <table class="table" id="abos-table">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Entreprise</th>
                            <th>Adresse</th>
                            <th>Exemplaires</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!$abo->abonnements->isEmpty())
                                @foreach($abo->abonnements as $abonnement)
                                    <?php $user = $abonnement->user_adresse; ?>
                                    <tr>
                                        <td><a href="{{ url('admin/abonnement/'.$abonnement->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a></td>
                                        <td>
                                            {{ $abonnement->numero }}
                                            @if(!$abonnement->user_id && $abonnement->adresse_id)
                                                &nbsp; &nbsp;<i class="fa fa-bolt text-danger" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $user ? $user->name : '<p><span class="label label-warning">Duplicata</span></p>' !!}

                                            @if($abonnement->tiers_user_id || $abonnement->tiers_id)
                                                <p><strong>Tiers payant:</strong></p>
                                                {{ $abonnement->user_facturation->name }}<br/>
                                                {!! $abonnement->user_facturation->adresse.'<br/>'.$abonnement->user_facturation->npa.' '.$abonnement->user_facturation->ville !!}
                                            @endif
                                        </td>
                                        <td>{!! $user && ($user->company != $user->name) ? $user->company  : '' !!}</td>
                                        <td>{!! $user ? $user->adresse.'<br/>'.$user->npa.' '.$user->ville : '' !!}</td>
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