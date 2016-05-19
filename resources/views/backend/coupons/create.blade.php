@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/coupon') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/coupon') }}" method="POST" class="form-horizontal">
                    {!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Ajouter coupon</h4>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type de rabais</label>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="type" id="typeSelect">
                                    <option value="global">Sur toute la commande</option>
                                    <option value="product">Sur un ou plusieurs produits</option>
                                    <option value="shipping">Frais de port gratuit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="productSelect" style="display:none;">
                            <label class="col-sm-3 control-label">Choix des produits</label>
                            <div class="col-sm-9 col-xs-12">
                                @if(!$products->isEmpty())
                                <select name="product_id[]" multiple="multiple" id="multi-select">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valeur</label>
                            <div class="col-sm-5 col-xs-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="value">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date d'expiration</label>
                            <div class="col-sm-5 col-xs-6">
                                <input type="text" class="form-control datePicker" name="expire_at">
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer un coupon</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop