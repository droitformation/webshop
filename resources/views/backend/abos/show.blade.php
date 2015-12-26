@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-10">
                            <form action="{{ url('admin/factures/'.$abo->id) }}" method="POST" class="form-inline">{!! csrf_field() !!}
                                <div class="form-group">
                                    <select class="form-control" name="product_id">
                                        @if(!$abo->products->isEmpty())
                                            @foreach($abo->products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <button class="btn btn-info">Liste des factures</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href="{{ url('admin/abonnement/create/'.$abo->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter un abonné</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-heading"><h4><i class="fa fa-edit"></i> Abonnements</h4></div>
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