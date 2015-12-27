@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/factures/'.$facture->product_id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;&Eacute;diter facture</h4>
                </div>
                <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Statut</label>
                            <div class="col-sm-3 col-xs-6">
                                @if($facture->payed_at)
                                    <span class="label label-success" style="line-height: 36px;">Payé</span>
                                @else
                                    <span class="label label-default" style="line-height: 36px;">Ouverte</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date de la facture</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control datePicker" value="{{ $facture->created_at->format('Y-m-d') }}" name="created_at">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date de payement</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control datePicker" value="{{ $facture->payed_at ? $facture->payed_at->format('Y-m-d') : '' }}" name="payed_at">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="{{ $abonnement->price_cents }}" name="price">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Produits</label>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="products_id">
                                    @if(!$abo->products->isEmpty())
                                        @foreach($abo->products as $product)
                                            <option {{ $product->id == $facture->product_id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Facture</label>
                            <div class="col-sm-3 col-xs-6">
                                @if($facture->abo_facture)
                                    <a class="btn btn-sm btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        {!! Form::hidden('id', $facture->id ) !!}
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop