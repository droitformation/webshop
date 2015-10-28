@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">
    @if ( !empty($user) )

           <div class="col-md-6"><!-- col -->

               <div class="panel panel-midnightblue">
                   <div class="panel-body">

                       <form action="{{ url('admin/user/'.$user->id) }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form form-horizontal">
                           <input type="hidden" name="_method" value="PUT">
                           {!! csrf_field() !!}

                            <h3>Compte</h3>
                            <hr/>
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Prénom</label>
                                <div class="col-sm-7">
                                    {!! Form::text('first_name', $user->first_name , array('class' => 'form-control') ) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Nom</label>
                                <div class="col-sm-7">
                                    {!! Form::text('last_name', $user->last_name , array('class' => 'form-control') ) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-7">
                                    {!! Form::text('email', $user->email , array('class' => 'form-control') ) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Mot de passe</label>
                                <div class="col-sm-4">
                                    <a class="btn btn-warning" href="#">Modifier le mot de passe</a>
                                </div>
                            </div>
                            {!! Form::hidden('id', $user->id ) !!}
                            <button class="btn btn-primary pull-right" type="submit">Enregistrer</button>
                       </form>
                   </div>
               </div>

               <!-- ADRESSES -->
               <div class="panel-group" id="accordion">
                   @if(!$user->adresses->isEmpty())
                       @foreach ($user->adresses as $adresse)
                           <div class="panel panel-info">
                               <div class="panel-body">
                                   <h3 style="margin-bottom: 0;"><i class="fa fa-map-marker"></i>
                                       &nbsp;Adresse {{ $adresse->type_title }}  &nbsp;{!! $adresse->livraison ? '<span class="label label-default">livraison</span>' : '' !!}
                                       <a role="button" class="btn btn-sm btn-info pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$adresse->type}}">Voir</a>
                                   </h3>
                                   <div id="collapse{{$adresse->type}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                       @include('backend.users.adresse',['adresse' => $adresse])
                                   </div>
                               </div>
                           </div>
                       @endforeach
                   @endif
               </div>


           </div>
          <div class="col-md-6">

              <div class="panel panel-midnightblue">
                  <div class="panel-body" style="padding-bottom: 0;">
                      <h3>Inscriptions</h3>
                      @if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

                          <?php $grouped = $user->inscriptions->groupBy('status'); ?>

                          @foreach($grouped as $inscriptions)
                              <div class="list-group">
                                  <div class="list-group-item-title bg-{{ $inscriptions->first()->status_name['color'] }}">
                                      {{ $inscriptions->first()->status_name['status'] }}
                                  </div>
                                  @foreach($inscriptions as $inscription)
                                      <?php $inscription->load('colloque'); ?>
                                      <a href="{{ url('profil/inscription/'.$inscription->id) }}" class="list-group-item">
                                          <h5>{{ $inscription->colloque->titre }}</h5>
                                          <p><span class="glyphicon glyphicon-send" aria-hidden="true"></span> &nbsp;{{ $inscription->created_at->format('d/m/Y') }}</p>
                                      </a>
                                  @endforeach
                              </div>
                          @endforeach
                      @endif
                  </div>
              </div>


          </div>
        <div class="col-md-6">

            <div class="panel panel-midnightblue">
                <div class="panel-body" style="padding-bottom: 0;">
                    <h3>Commandes</h3>

                    @if(!$user->orders->isEmpty())
                        <?php $user->orders->load('products','shipping','coupon','payement'); ?>
                        <?php $orders = $user->orders->sortByDesc('created_at'); ?>
                        <table class="table order-list">
                            <tr>
                                <th>Commande n°</th>
                                <th>Passée le </th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                            @foreach($orders as $order)

                                <tr>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td>{{ $order->price_cents }}</td>
                                    <td><span class="label label-{{ $order->status_code['color'] }}">{{ $order->status_code['status'] }}</span></td>
                                    <td class="text-right">
                                        <a data-toggle="collapse" href="#order_no_{{ $order->id }}">Voir la commande</a>
                                    </td>
                                </tr>

                                @if(!empty($order->products))

                                    <?php $grouped = $order->products->groupBy('id'); ?>
                                    <tr>
                                        <td colspan="5" class="nopadding">

                                            <div class="collapse" id="order_no_{{ $order->id }}">
                                                <div class="well">
                                                    @foreach($grouped as $product)
                                                        <div class="row order-item">
                                                            <div class="col-md-1">
                                                                <a href="#"><img height="40" src="{{ asset('files/products/'.$product->first()->image) }}" alt=""></a>
                                                            </div>
                                                            <div class="col-md-8">{{ $product->first()->title }}</div>
                                                            <div class="col-md-1"><p class="text-right">{{ $product->count() }} x</p></div>
                                                            <div class="col-md-2"><p class="text-right">{{ $product->first()->price_cents }} CHF</p></div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-9"><p class="text-right">Payement</p></div>
                                                    <div class="col-md-3"><p class="text-right">{{ $order->payement->title }}</p></div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <?php
                                                        $facture = public_path('files/shop/factures/facture_'.$order->order_no.'.pdf');

                                                        if ($order->facture)
                                                        {
                                                            echo '<a target="_blank" href="'.$order->facture.'" class="btn btn-success">Facture en pdf</a>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-7">
                                                        @if(isset($order->coupon))
                                                            <p class="text-right"><strong>Rabais appliqué <small class="text-muted">{{ $order->coupon->title }}</small></strong></p>
                                                        @endif
                                                        <p class="text-right">Frais de port</p>
                                                        <p class="text-right"><strong>Total</strong></p>
                                                    </div>
                                                    <div class="col-md-3">

                                                        @if($order->coupon_id > 0)
                                                            @if( $order->coupon->type == 'shipping')
                                                                <?php $order->coupon_id->load('coupon'); ?>
                                                                <p class="text-right text-muted">Frais de port offerts</p>
                                                            @else
                                                                <p class="text-right">- {{ $order->coupon->value }}%</p>
                                                            @endif
                                                        @endif

                                                        <p class="text-right">{{ $order->shipping->price_cents }} CHF</p>
                                                        <p class="text-right">{{ $order->price_cents }} CHF</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            @endforeach
                        </table>
                    @else
                        <p>Encore aucune commandes</p>
                    @endif
                </div>
            </div>



        </div>

    @endif
</div>
<!-- end row -->

@stop