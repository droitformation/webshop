@extends('backend.layouts.master')
@section('content')
    
    <div class="row">
        <div class="col-md-12">
            <h3>{{ $abo->title }} &Eacute;dition {{ $abo->current_product->reference }}</h3>

            <div class="options text-left" style="margin-bottom: 10px;">
                <a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h4>Factures</h4>
                    <table class="table simple-table">
                        <thead>
                            <tr>
                                <th width="20px;">Action</th>
                                <th width="20px;">Numero</th>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Rappels</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$factures->isEmpty())
                            @foreach($factures as $facture)
                                <tr>
                                    <td><a href="{{ url('admin/facture/'.$facture->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $facture->abonnement->numero }}</td>
                                    <td>{{ $facture->abonnement->user->name }}</td>
                                    <td>{{ $facture->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td>
                                        @if($facture->payed_at)
                                            <p><span class="label label-success">Payé le {!! $facture->payed_at->formatLocalized('%d %B %Y') !!}</span></p>
                                        @else
                                            <p><span class="label label-default">Ouverte</span></p>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$facture->rappels->isEmpty())

                                            @foreach($facture->rappels as $rappel)
                                                <p><strong>{!! $rappel->created_at->formatLocalized('%d %B %Y') !!}</strong></p>
                                            @endforeach

                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="Facture" class="btn btn-danger btn-sm deleteAction">x</button>
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