@extends('backend.layouts.master')
@section('content')

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-2">
            <p><a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
        <div class="col-md-8">
            <img class="thumbnail" style="height: 60px; float:left; margin-right: 15px;padding: 2px;" src="{{ asset('files/products/'.$product->image) }}" />
            <h3 style="margin: 0;">{{ $abo->title }}</h3>
            <p>&Eacute;dition {{ $product->reference }}</p>
        </div>
        <div class="col-md-2 text-right">
            <p><a href="{{ url('admin/facture/generate/'.$product->id) }}" class="btn btn-warning">Generate</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-xs-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body" style="padding-bottom: 5px;">

                    <form action="{{ url('admin/abonnement/export') }}" method="POST" class="form-inline">{!! csrf_field() !!}
                        <input type="hidden" name="edition" value="{{ $product->edition }}">
                        <input type="hidden" name="reference" value="{{ $product->reference }}">
                        <input type="hidden" name="abo_id" value="{{ $abo->id }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <p>
                            <select class="form-control" style="width:100%" name="type">
                                <option value="facture">Toutes les factures</option>
                                <option value="rappel">Tous les rappels</option>
                            </select>
                        </p>
                        <p><button class="btn btn-info btn-block"><i class="fa fa-download"></i> &nbsp;Exporter et lier</button></p>
                    </form>

                </div>
            </div>
            <div class="panel panel-midnightblue">
                <div class="panel-body" style="padding-bottom: 0;">
                    <h4 style="margin-top: 0;">Factures liés</h4>
                    @if(!empty($files))
                        <div class="list-group">
                            @foreach($files as $file)
                                <?php $name = explode('/',$file); ?>
                                <a href="{{ asset($file.'?'.rand(1000,2000)) }}" target="_blank" class="list-group-item">
                                    <i class="fa fa-download"></i>&nbsp; {{ end($name) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-10 col-xs-12">
            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-folder-open"></i> Factures</h4>
                </div>
                <div class="panel-body">
                    <table class="table simple-table">
                        <thead>
                            <tr>
                                <th width="20px;">Action</th>
                                <th width="20px;">Numero</th>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Facture</th>
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
                                    <td>
                                        @if($facture->abonnement->user)
                                            {{ $facture->abonnement->user->name }}
                                        @elseif($facture->abonnement->originaluser)
                                            {{ $facture->abonnement->originaluser->name }}
                                        @else
                                            <p><span class="label label-warning">Duplicata</span></p>
                                        @endif
                                    </td>
                                    <td>{{ $facture->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td>
                                        @if($facture->abo_facture)
                                            <a class="btn btn-sm btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
                                        @endif
                                    </td>
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