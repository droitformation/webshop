@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-2">
            <p><a href="{{ url('admin/orders/back') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
        <div class="col-md-5">
            <form action="{{ url('admin/order/recalculate') }}" method="POST" class="pull-right">{!! csrf_field() !!}
                <input name="id" value="{{ $order->id }}" type="hidden">
                <button type="submit" class="btn btn-warning btn-sm">Calculer les frais de port automatiquement</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/order/'.$order->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

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
                                <input type="text" class="form-control" disabled value="{{ $order->total_with_shipping }}" name="price">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Client</label>
                            <div class="col-sm-6 col-xs-8" id="appComponent">
                                <list-autocomplete type="user_id" chosen_id="{{ $order->user_id }}"></list-autocomplete>
                            </div>
                        </div>

                        @if(!$order->user_id)
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-12 control-label">
                                    Adresse sans utilisateur <p><strong class="text-danger">A changer</strong></p>
                                </label>
                                <div class="col-sm-8 col-xs-12">
                                    <div class="change_abo_user">
                                        <?php $adresse = $order->order_adresse; ?>
                                        <strong>
                                            <a target="_blank" href="{{ url('admin/adresse/'.$adresse->id) }}">{{ $adresse->civilite_title }} {{ $adresse->name }}</a>
                                        </strong><br>
                                        {!! !empty($adresse->company) ? $adresse->company.'<br>' : '' !!}
                                        {{ $adresse->adresse }}<br>
                                        {!! !empty($adresse->cp) ? $adresse->cp_trim.'<br>' : '' !!}
                                        {{ $adresse->npa }} {{ $adresse->ville }}<br>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date de la facture</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control datePicker" value="{{ $order->created_at->format('Y-m-d') }}" name="created_at">
                            </div>
                        </div>

                        <div class="well pt-0">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Frais de ports</label>
                                <div class="col-sm-5 col-xs-8">
                                    @if(!$shippings->isEmpty())
                                        <select class="form-control" name="shipping_id">
                                            <option value="">Choix</option>
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
                                        <option value="">Choix</option>
                                        @foreach(range(1,50) as $paquet)
                                            <option {{ $order->paquet == $paquet ? 'selected' : '' }} value="{{ $paquet }}">{{ $paquet }} paquets</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if(!$order->paquets->isEmpty())

                                <div class="form-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-5 col-xs-8">
                                        <p class="delimiter">-- ou --</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Frais de ports calculés</label>
                                    <div class="col-sm-5 col-xs-8">
                                        <?php
                                            $paquets = collect($order->paquets)->groupBy(function ($item, $key) {
                                                return ($item->shipping->value/1000).' Kg | '.$item->shipping->price_cents;
                                            })->map(function ($item, $key) {
                                                return $item->sum('qty');
                                            });
                                        ?>
                                        <ul class="list-group mb-0">
                                            @if(!$paquets->isEmpty())
                                                @foreach($paquets as $count => $paquet)
                                                    <li class="list-group-item"><span class="label label-default" style="min-width: 30px;">{{ $paquet }}x</span> &nbsp;{{ $count }}</li>
                                                @endforeach
                                            @endif
                                        </ul>

                                    </div>
                                </div>
                            @endif

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

                        <!-- New version -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Réferences</label>
                            <div class="col-sm-8 col-xs-8">
                                <div class="input-group">
                                    <span class="input-group-addon">N° référence</span>
                                    <input type="text" class="form-control" value="{{ isset($order->references) ? $order->references->reference_no : '' }}" name="reference_no">
                                </div><br>

                                <div class="input-group">
                                    <span class="input-group-addon">N° commande</span>
                                    <input type="text" class="form-control" value="{{ isset($order->references) ? $order->references->transaction_no : '' }}" name="transaction_no">
                                </div>
                            </div>
                        </div>

                        <?php $messages = unserialize($order->comment); ?>
                        <?php $warning = isset($messages['warning']) ? $messages['warning'] : old('message.warning'); ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><i class="fa fa-info-circle"></i>&nbsp; Phrase d'information</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #f1c40f; padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="comment[warning]" value="{{ $warning }}">
                                </div>
                            </div>
                        </div>

                        <?php $special = isset($messages['special']) ? $messages['special'] : old('message.special'); ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><i class="fa fa-info-circle"></i>&nbsp; Information pour librairies</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #85c744;padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="comment[special]" value="{{ $special }}">
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