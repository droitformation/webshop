@extends('backend.layouts.master')
@section('content')

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <img class="thumbnail" style="height: 80px; float:left; margin-right: 15px;padding: 5px;" src="{{ asset('files/products/'.$abo->current_product->image) }}" />
            <h3>{{ $abo->title }}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <p><a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
            <div class="panel panel-midnightblue">
                <div class="panel-body" style="padding-bottom: 0;">
                    <h4>Factures</h4>
                    <div class="list-group">
                        @if(!$abo->products->isEmpty())
                            @foreach($abo->products as $product)
                                <a class="list-group-item" href="{{ url('admin/factures/'.$product->id) }}">
                                    &nbsp;<i class="fa fa-folder-open"></i>&nbsp; &Eacute;dition <strong>{{ $product->reference }}</strong>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <p class="text-right"><a href="{{ url('admin/abonnement/create/'.$abo->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter un abonné</a></p>
            <div class="panel panel-midnightblue">
                <div class="panel-heading"><h4><i class="fa fa-tag"></i> Abonnements</h4></div>
                <div class="panel-body">
                    <table class="table" id="generic">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Entreprise</th>
                            <th>Exemplaires</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!$abo->abonnements->isEmpty())
                                @foreach($abo->abonnements as $abonnement)
                                    <tr>
                                        <td><a href="{{ url('admin/abonnement/'.$abonnement->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                        <td>{{ $abonnement->numero }}</td>
                                        <td>{{ $abonnement->user->name }}</td>
                                        <td>{{ $abonnement->user->company }}</td>
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
        </div>

    </div>

@stop