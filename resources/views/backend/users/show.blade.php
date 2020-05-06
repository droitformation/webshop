@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-6"><!-- col -->
        <?php $back = session()->has('return_path.user_'.$user->id) ? session()->get('return_path.user_'.$user->id) : redirect()->getUrlGenerator()->previous(); ?>
        <?php $back = ($back != url('admin/users') && $back != url()->current() && $back != url()->current().'?path') ? $back : url('admin/users/back'); ?>
        <a href="{{ $back }}" class="btn btn-default">Retour</a>
    </div>
    <div class="col-md-6 text-right"><!-- col -->
        <a href="{{ url('admin/adresse/create/'.$user->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une adresse</a>
    </div>
</div>
<br>
<!-- start row -->
<div class="row" id="appVue">
    @if (!empty($user))
           <div class="col-md-12"><!-- col -->

              <div class="row">
                  <div class="col-md-4">

                      <!-- Main form start -->
                      <form action="{{ url('admin/user/'.$user->id) }}" data-validate="parsley" method="POST" class="validate-form">
                          <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
                          <input value="{{ $user->id }}" type="hidden" name="id">

                          <div class="panel panel-midnightblue">
                              <div class="panel-body">

                                  @if(!$user->roles->isEmpty())
                                      @foreach($user->roles as $role)
                                          <span class="label label-info pull-right">{{ $role->name }}</span>
                                      @endforeach
                                  @endif

                                  <h3><i class="fa fa-user"></i> &nbsp;Compte</h3>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Prénom</label>
                                      <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}">
                                  </div>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Nom</label>
                                      <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                                  </div>
                                  <p style="margin-bottom: 15px;">
                                      <strong class="text-danger">OU</strong> &nbsp;&nbsp;
                                      <span class="text-muted">(Prénom et nom sont pris en compte en premier si existant)</span>
                                  </p>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Entreprise</label>
                                      <input type="text" name="company" class="form-control" value="{{ $user->company }}">
                                  </div>

                                  <div class="form-group">
                                      <label for="message" class="control-label">Email (Utilisé pour login)</label>
                                      <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                  </div>

                                  <div class="form-group">
                                      <label for="message" class="control-label">Email global si aucun email personnel</label>
                                      <input type="text" autocomplete="false" name="username" class="form-control" value="{{ $user->username != $user->email ?  $user->username : '' }}">
                                  </div>

                                  <a class="text-danger" data-toggle="collapse" href="#changePassword" href="#"><i class="fa fa-exclamation-circle"></i>&nbsp;Modifier le mot de passe</a>
                                  <div class="collapse" id="changePassword">
                                      <div class="form-group">
                                           <label for="pasword" class="control-label">Nouveau mot de passe</label>
                                           <input type="password" id="newpassword" name="password" autocomplete="false" value="" class="form-control">
                                      </div>
                                  </div><br>
                                  <div class="form-group">
                                      <button class="btn btn-primary pull-right" id="updateUser{{ $user->id }}" type="submit">Enregistrer</button>
                                  </div>
                                  <div class="clearfix"></div><hr>
                                  <a href="{{ url('admin/user/confirm/'.$user->id) }}" class="btn btn-danger btn-sm deleteAction">Supprimer le compte</a>
                              </div>
                          </div>

                          <div class="panel panel-midnightblue">
                              <div class="panel-body">
                                  <h3 class="flex flex-row justify-between">
                                      <div><i class="fa fa-key"></i> &nbsp;Role et accès</div>
                                      <a class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#collapseRoles">Voir</a>
                                  </h3>
                                  <div class="collapse" id="collapseRoles">
                                      @include('backend.users.partials.roles', ['roles' => $roles, 'user' => $user])
                                      <button class="btn btn-primary pull-right" id="updateUser{{ $user->id }}" type="submit">Enregistrer</button>
                                  </div>
                              </div>
                          </div>
                      </form>
                      <!-- Main form end -->

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3 class="flex flex-row justify-between">
                                  <div><i class="fa fa-gift"></i> &nbsp;Rabais sur inscription</div>
                                  <a class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#collapseRabais" aria-expanded="false" aria-controls="collapseRabais">
                                      Voir
                                  </a>
                              </h3>
                              <div class="collapse" id="collapseRabais">

                                  @if(!$user->used_rabais->isEmpty())
                                      <ul>
                                          @foreach($user->used_rabais as $used)
                                              <li>{{ $used->title }}</li>
                                          @endforeach
                                      </ul>
                                  @endif

                                  <div class="form-group">
                                      <label class="control-label"><h4>Ajouter un rabais</h4></label>&nbsp;
                                      <div id="rabais_tags" class="well well-sm">
                                          <ul id="rabais" data-id="{{ $user->id }}">
                                              {!! $user->rabais_list !!}
                                          </ul>
                                      </div>
                                      <p id="tagused" class="text-danger" style="display: none;">A déjà obtenu et utilisé ce rabais</p>
                                  </div>
                              </div>
                          </div>
                      </div>

                      @if(!$user->abos->isEmpty())
                          <div class="panel panel-midnightblue">
                              <div class="panel-body">
                                  <h3><i class="fa fa-table"></i> &nbsp;Abonnements</h3>
                                  <ul class="list-unstyled">
                                      @foreach($user->abos as $abo)
                                          <li><a href="{{ url('admin/abonnement/'.$abo->id) }}"><i class="fa fa-bookmark"></i> &nbsp;{{ $abo->abo->title }}</a></li>
                                      @endforeach
                                  </ul>
                              </div>
                          </div>
                      @endif

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-tags"></i>&nbsp;Spécialisations</h3>
                              @if(isset($user->adresse_facturation))
                                  <ul id="specialisations" data-model="adresse" data-id="{{ $user->adresse_facturation->id }}">
                                      {!! $user->adresse_specialisations !!}
                                  </ul>
                                  <hr/>
                                  <h3><i class="fa fa-bookmark"></i> &nbsp;Membre</h3>
                                  <ul id="members" data-id="{{ $user->adresse_facturation->id }}">
                                      {!! $user->adresse_membres !!}
                                  </ul>
                              @endif
                          </div>
                      </div>

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-envelope"></i>&nbsp; Newsletters</h3>

                              @if(!$account->subscriptions()->isEmpty())
                                  @foreach($account->subscriptions() as $email => $subscriptions)
                                      <h4>{{ $email }}</h4>
                                      <ul class="list-group">
                                         @foreach($subscriptions as $subscription)
                                              <li class="list-group-item">
                                                  {{ $subscription->titre }}
                                                  <form action="{{ url('admin/user/unsubscribe') }}" method="POST" class="pull-right">{!! csrf_field() !!}
                                                     <input type="hidden" name="email" value="{{ $email }}">
                                                     <input type="hidden" name="newsletter_id" value="{{ $subscription->id }}">
                                                     <button type="submit" class="btn btn-danger btn-xs">désinscrire</button>
                                                  </form>
                                              </li>
                                         @endforeach
                                      </ul>
                                  @endforeach
                              @endif

                          </div>
                      </div>

                  </div>
                  <div class="col-md-8">

                      <!-- ADRESSES -->
                      <div class="panel-group" id="accordion">

                          @if(!$user->adresses->isEmpty())
                              @foreach ($user->adresses->sortBy('type') as $adresse)
                                  <div class="panel panel-midnightblue">
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-md-7">
                                                  <h4 style="margin-bottom: 5px;"> {{ $adresse->name }} </h4>
                                                  <p class="text-muted">
                                                      <i class="fa fa-map-marker"></i>&nbsp;Adresse {{ $adresse->type_title }}
                                                      {!! $adresse->livraison ? '<small>livraison</small>' : '' !!}
                                                  </p>
                                              </div>
                                              <div class="col-md-4 text-right">
                                                  @if($adresse->user_id > 0 && !$adresse->livraison)
                                                      <form action="{{ url('admin/adresse/livraison') }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
                                                          <input type="hidden" name="adresse_id" value="{{ $adresse->id }}">
                                                          <input type="hidden" name="user_id" value="{{ $adresse->user_id }}">
                                                          <button type="submit" class="btn btn-inverse btn-sm">Définir comme adresse de livraison</button>
                                                      </form>
                                                  @endif
                                              </div>
                                              <div class="col-md-1">
                                                  <a role="button" class="btn btn-sm btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$adresse->id}}">Voir</a>
                                              </div>
                                          </div>
                                          <div id="collapse{{$adresse->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                              @include('backend.adresses.partials.update',['adresse' => $adresse, 'user' => $user])
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          @endif
                      </div>

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3>
                                  <i class="fa fa-table"></i> &nbsp;Inscriptions
                                  <a href="{{ url('admin/inscription/create') }}" class="btn btn-success btn-sm  pull-right">Ajouter une inscription</a>
                              </h3>

                              @include('backend.users.partials.inscriptions')
                          </div>
                      </div>

                      @if(isset($user->inscription_groupes) && !$user->inscription_groupes->isEmpty())
                          <div class="panel panel-midnightblue">
                              <div class="panel-body">
                                  <h3><i class="fa fa-table"></i> &nbsp;Inscriptions groupés</h3>
                                  @include('backend.users.partials.groups', ['inscriptions_groupe' => $user->inscription_groupes])
                              </div>
                          </div>
                      @endif

                      @if(isset($user->participations) && !$user->inscription_participations->isEmpty())
                          <div class="panel panel-midnightblue">
                              <div class="panel-body">
                                  <h3><i class="fa fa-table"></i> &nbsp;Participe aux inscriptions</h3>
                                  @include('backend.users.partials.participations')
                              </div>
                          </div>
                      @endif

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes</h3>
                              @include('backend.users.partials.commandes', ['orders' => $user->orders])
                          </div>
                      </div>

                      @if(!$user->adresses->isEmpty())
                          @foreach($user->adresses as $adresse)
                              @if(!$adresse->orders->isEmpty())
                                  <div class="panel panel-midnightblue">
                                      <div class="panel-body">
                                          <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes via adresse</h3>
                                          @include('backend.users.partials.commandes', ['orders' => $adresse->orders])
                                      </div>
                                  </div>
                              @endif
                          @endforeach
                      @endif

                  </div>
              </div>

        </div>
    @endif
</div>
<!-- end row -->

@stop