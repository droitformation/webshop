@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <p><a href="{{ url('admin/orders') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/order/'.$order->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Editer la commande</h4>

                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label"></label>
                            <div class="col-sm-4 col-xs-12">
                                @if($order->facture )
                                    <a target="_blank" href="{{ $order->facture }}?{{ rand(1,1000) }}" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> &nbsp;Facture en pdf</a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label">Coupon</label>
                            <div class="col-sm-5 col-xs-12">
                                <select name="coupon_id" class="form-control">
                                    <option value="">Choix</option>
                                    @if(!$coupons->isEmpty())
                                        @foreach($coupons as $coupon)
                                            <option {{ $coupon->id == $order->coupon_id ? 'selected' : '' }} value="{{ $coupon->id }}">{{ $coupon->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label">N°</label>
                            <div class="col-sm-5 col-xs-12">
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
                            <div class="col-sm-6 col-xs-8">
                                @include('backend.partials.search-adresse', ['adresse_id' => $order->order_adresse->id, 'type' => 'adresse_id'])
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date de la facture</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control datePicker" value="{{ $order->created_at->format('Y-m-d') }}" name="created_at">
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
                            <label class="col-sm-3 control-label">Nombre de paquets</label>
                            <div class="col-sm-5 col-xs-8">
                                <select name="paquet" class="form-control">
                                    @foreach(range(1,50) as $paquet)
                                        <option {{ $order->paquet == $paquet ? 'selected' : '' }} value="{{ $paquet }}">{{ $paquet }} paquets</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><i class="fa fa-dollar"></i>&nbsp; Modifier les taux de tva</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="tva[taux_reduit]" value="{{ old('tva.taux_reduit') }}" placeholder="Réduit"><span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="tva[taux_normal]" value="{{ old('tva.taux_normal') }}" placeholder="Normal"><span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><i class="fa fa-info-circle"></i>&nbsp; Phrase d'information</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #f1c40f; padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="message[warning]" value="{{ old('message.warning') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><i class="fa fa-info-circle"></i>&nbsp; Information pour librairies</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #85c744;padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="message[special]" value="{{ old('message.special') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label text-danger">Payé le</label>
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