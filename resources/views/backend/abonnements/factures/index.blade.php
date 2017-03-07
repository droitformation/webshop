@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <p><a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux abonnées</a></p>
        </div>
        <div class="col-md-6 text-right">
            <p><a href="{{ url('admin/rappel/'.$product->id) }}" class="btn btn-brown"><i class="fa fa-exclamation-triangle"></i> &nbsp;Voir les rappels</a></p>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body" style="padding-bottom: 10px;">
            <div class="row">
                <div class="col-md-2">
                    <img class="thumbnail" style="height: 40px; float:left; margin-right: 15px;padding: 2px;" src="{{ secure_asset('files/products/'.$product->image) }}" />
                    <h3 style="margin-bottom:0;line-height:20px;font-size: 18px;"><a href="{{ url('admin/abo/'.$abo->id) }}">{{ $abo->title }}</a></h3>
                    <p style="margin-bottom: 5px;">&Eacute;dition {{ $product->reference.' '.$product->edition }}</p>
                </div>
                <div class="col-md-8">
                    <form action="{{ url('admin/abo/generate') }}" method="POST" class="pull-right">
                        <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="worker" value="facture">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-file-o"></i> &nbsp;Générer toutes les factures</button>
                    </form>
                </div>
                <div class="col-md-1 text-right">
                    <form action="{{ url('admin/abo/bind') }}" method="POST">
                        <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="worker" value="facture">
                        <button type="submit" class="btn btn-default" title="Re-attacher toutes les factures"><i class="fa fa-link"></i> &nbsp; Attacher</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3 style="margin-bottom: 30px;" class="pull-left">Factures pour l'&eacute;dition {{ $product->reference.' '.$product->edition }}</h3>
                    <div class="pull-right">
                        @if(!empty($files))
                            <h4 style="margin-bottom: 10px; margin-top: 0;">Rappels liés</h4>
                            <div class="btn-group">
                                @foreach($files as $file)
                                    <?php $name = explode('/',$file); ?>
                                    <a href="{{ secure_asset($file.'?'.rand(1000,2000)) }}" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-download"></i>&nbsp; {{ end($name) }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="clearfix"></div><br/>
                    <table class="table" id="abos-table">
                        <thead>
                            <tr>
                                <th class="col-md-1">Action</th>
                                <th class="col-md-1">Numero</th>
                                <th class="col-md-3">Nom</th>
                                <th class="col-md-2">Date</th>
                                <th class="col-md-1">Facture</th>
                                <th class="col-md-3">Status</th>
                                <th class="col-md-1"></th>
                            </tr>
                        </thead>
                        <tbody id="">
                        @if(!$factures->isEmpty())
                            @foreach($factures as $facture)
                                @if(isset($facture->abonnement) && !$facture->abonnement->deleted_at)
                                <tr>
                                    <td><a href="{{ url('admin/facture/'.$facture->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a></td>
                                    <td>{{ $facture->abonnement->numero }}</td>
                                    <td>
                                        @if($facture->abonnement->user)
                                            <a href="{{ url('admin/abonnement/'.$facture->abonnement->id) }}">{{ $facture->abonnement->user->name }}</a>
                                        @elseif($facture->abonnement->originaluser)
                                            <a href="{{ url('admin/abonnement/'.$facture->abonnement->id) }}">{{ $facture->abonnement->originaluser->name }}</a>
                                        @else
                                            <p><span class="label label-warning">Duplicata</span></p>
                                        @endif
                                    </td>
                                    <td>{{ $facture->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td>
                                        @if($facture->doc_facture)
                                            <a class="btn btn-default btn-sm" target="_blank" href="{{ secure_asset($facture->doc_facture) }}">Facture pdf</a>
                                        @else
                                            <form action="{{ url('admin/facture/make') }}" method="POST" class="form-horizontal">
                                                {!! csrf_field() !!}
                                                <input name="id" type="hidden" value="{{ $facture->id }}">
                                                <button class="btn btn-default btn-sm">générer</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        @include('backend.abonnements.partials.payed',['facture' => $facture])
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="Facture" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@stop