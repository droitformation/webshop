@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/orders') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Editer la commande</h4>
                </div>
                <form action="{{ url('admin/order/'.$order->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">N°</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" disabled value="{{ $order->order_no }}" name="order_no">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" disabled value="{{ $order->price_cents }}" name="price">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Client</label>
                            <div class="col-sm-5 col-xs-8">

                                <!-- Autocomplete for adresse -->
                                <div class="autocomplete-wrapper">
                                    <div class="input-adresse" data-adresse="{{ $order->order_adresse->id }}" data-type="adresse">
                                        <input type="hidden" class="form-control" value="{{ $order->order_adresse->id }}" name="adresse_id">
                                    </div>
                                    <div class="choice-adresse"></div>
                                    <div class="collapse adresse-find">
                                        <div class="form-group">
                                            <input id="search-adresse" class="form-control search-adresse" placeholder="Chercher une adresse..." type="text">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Autocomplete for adresse -->

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date de la facture</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control datePicker" value="{{ $order->created_at->format('Y-m-d') }}" name="payed_at">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Frais de ports</label>
                            <div class="col-sm-5 col-xs-8">
                                @if(!$shippings->isEmpty())
                                    <select class="form-control" name="shipping_id">
                                        @foreach($shippings as $shipping)
                                            <option {{ $order->shipping_id ==  $shipping->id ? 'selected' : '' }} value="{{ $shipping->id }}">{{ $shipping->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Payé le</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control datePicker" value="{{ $order->payed_at ? $order->payed_at->format('Y-m-d') :'' }}" name="payed_at">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-5 col-xs-8">
                                <hr/>
                                <p><strong>Total</strong><span class="pull-right">{{ $order->total_with_shipping }} CHF</span></p>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $order->id }}" name="id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop