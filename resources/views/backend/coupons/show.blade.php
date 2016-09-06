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
                <form action="{{ url('admin/coupon/'.$coupon->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}
                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Editer coupon</h4>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type de rabais</label>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="type" id="typeSelect">
                                    <option {{ ($coupon->type == 'global' ?  'selected' : '') }} value="global">Sur toute la commande</option>
                                    <option {{ ($coupon->type == 'product' ? 'selected' : '') }} value="product">Sur un ou plusieurs produits</option>
                                    <option {{ ($coupon->type == 'shipping'? 'selected' : '') }} value="shipping">Frais de port gratuit</option>
                                </select>
                            </div>
                        </div>

                        <?php $choices = $coupon->products->pluck('id')->all(); ?>

                        <div class="form-group" id="productSelect" {{ ($coupon->type == 'product' ? 'style="display:block;"' : 'style="display:none;"') }}>
                            <label class="col-sm-3 control-label">Choix des produits</label>
                            <div class="col-sm-9 col-xs-12">
                                @if(!$products->isEmpty())
                                    <select name="product_id[]" multiple="multiple" id="multi-select">
                                        @foreach($products as $product)
                                            <option {{ (in_array($product->id,$choices) ? 'selected' : '') }} value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $coupon->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valeur</label>
                            <div class="col-sm-5 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $coupon->value }}" name="value">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <p class="help-block">Laisser vide avec frais de port gratuit</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date d'expiration</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control datePicker" value="{{ $coupon->expire_at->format('Y-m-d') }}" name="expire_at">
                            </div>
                        </div>

                        <input type="hidden" value="{{ $coupon->id }}" name="id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop