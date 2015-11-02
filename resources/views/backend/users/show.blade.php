@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">
    @if (!empty($user))
           <div class="col-md-12"><!-- col -->

              <div class="row">
                  <div class="col-md-4">

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">

                              <form action="{{ url('admin/user/'.$user->id) }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form">
                                  <input type="hidden" name="_method" value="PUT">
                                  {!! csrf_field() !!}
                                  <h3>Compte</h3>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Prénom</label>
                                      {!! Form::text('first_name', $user->first_name , array('class' => 'form-control') ) !!}
                                  </div>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Nom</label>
                                      {!! Form::text('last_name', $user->last_name , array('class' => 'form-control') ) !!}
                                  </div>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Email</label>
                                      {!! Form::text('email', $user->email , array('class' => 'form-control') ) !!}
                                  </div>
                                  <div class="form-group">
                                      <a class="text-danger" href="#"><i class="fa fa-exclamation-circle"></i>&nbsp;Modifier le mot de passe</a>
                                  </div>
                                  {!! Form::hidden('id', $user->id ) !!}
                                  <button class="btn btn-primary pull-right" type="submit">Enregistrer</button>
                              </form>
                          </div>
                      </div>


                      <div class="panel panel-info">
                          <div class="panel-body">
                              <h3>Spécialisations</h3>
                              <ul id="tags" data-model="adresse" data-id="{{ $user->adresse_facturation->id }}">
                                  @if(isset($user->adresse_facturation) && !$user->adresse_facturation->specialisations->isEmpty())
                                      @foreach($user->adresse_facturation->specialisations as $specialisation)
                                          <li>{{ $specialisation->title }}</li>
                                      @endforeach
                                  @endif
                              </ul>
                              <hr/>
                              <h3>Membre</h3>
                          </div>
                      </div>

                  </div>
                  <div class="col-md-8">

                      <!-- ADRESSES -->
                      <div class="panel-group" id="accordion">
                          @if(!$user->adresses->isEmpty())
                              @foreach ($user->adresses as $adresse)
                                  <div class="panel panel-primary">
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

                      <div class="panel panel-info">
                          <div class="panel-body">
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
                                              <div class="list-group-item">
                                                  <h5>
                                                      <a class="btn btn-xs btn-info" role="button" data-toggle="collapse" data-target="#collapseInsciption{{ $inscription->id }}">Détails</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                      {{ $inscription->colloque->titre }}
                                                      <p class="pull-right"><span class="fa fa-paper-plane"></span> &nbsp;{{ $inscription->created_at->format('d/m/Y') }}</p>
                                                  </h5>

                                                  <div class="collapse" id="collapseInsciption{{ $inscription->id }}">
                                                      <div class="row">
                                                          <div class="col-md-8">

                                                          </div>
                                                          <div class="col-md-4">
                                                              <p><strong>N°:</strong> {{ $inscription->inscription_no }}</p>
                                                              <p><strong>Prix:</strong> {{ $inscription->price_cents }}</p>
                                                          </div>
                                                      </div>

                                                  </div>

                                              </div>
                                          @endforeach
                                      </div>
                                  @endforeach
                              @endif
                          </div>
                      </div>

                      <div class="panel panel-info">
                          <div class="panel-body">
                              <h3>Commandes</h3>

                              @if(!$user->orders->isEmpty())
                                  <?php $user->orders->load('products','shipping','coupon','payement'); ?>
                                  <?php $orders = $user->orders->sortByDesc('created_at'); ?>
                                  <table class="table">
                                      <thead>
                                      <tr>
                                          <th>Commande n°</th>
                                          <th>Passée le </th>
                                          <th>Montant</th>
                                          <th>Statut</th>
                                          <th></th>
                                      </tr>
                                      </thead>
                                      <tbody>
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
                                      </tbody>
                                  </table>
                              @else
                                  <p>Encore aucune commandes</p>
                              @endif
                          </div>
                      </div>

                  </div>
              </div>

        </div>
    @endif
</div>
<!-- end row -->

@stop