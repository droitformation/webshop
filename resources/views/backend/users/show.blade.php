@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-6"><!-- col -->
        <a href="{{ redirect()->getUrlGenerator()->previous() }}" class="btn btn-default">Retour</a>
    </div>
    <div class="col-md-6 text-right"><!-- col -->
        <a href="{{ url('admin/adresse/make/'.$user->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une adresse</a>
    </div>
</div>
<br>
<!-- start row -->
<div class="row">
    @if (!empty($user))
           <div class="col-md-12"><!-- col -->

              <div class="row">
                  <div class="col-md-4">

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">

                              <form action="{{ url('admin/user/'.$user->id) }}" data-validate="parsley" method="POST" class="validate-form">
                                  <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

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
                                  <div class="form-group">
                                      <label for="message" class="control-label">Email</label>
                                      <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                  </div>

                                  <div class="form-group">
                                      <label class="control-label">Role</label>&nbsp;
                                      <label class="radio"><input {{ empty($user->all_roles) ? 'checked' : '' }} type="radio" name="role" value="0"> Utilisateur simple</label>
                                      @if(isset($roles))
                                          @foreach($roles as $role)
                                              <label class="radio">
                                                  <input type="radio" name="role" {{ in_array($role->id ,$user->all_roles) ? 'checked' : '' }} value="{{ $role->id }}"> {{ $role->name }}
                                              </label>
                                          @endforeach
                                      @endif
                                  </div>

                                  <a class="text-danger" data-toggle="collapse" href="#changePassword" href="#">
                                      <i class="fa fa-exclamation-circle"></i>&nbsp;Modifier le mot de passe
                                  </a>
                                  <div class="collapse" id="changePassword">
                                      <div class="form-group">
                                           <label for="pasword" class="control-label">Nouveau mot de passe</label>
                                          <input type="password" id="password" name="password" class="form-control">
                                      </div>
                                  </div><br>
                                  <div class="form-group">
                                      <input value="{{ $user->id }}" type="hidden" name="id">
                                      <button class="btn btn-primary pull-right" type="submit">Enregistrer</button>
                                  </div>
                                  <div class="clearfix"></div>
                              </form>
                              <hr>
                              <form action="{{ url('admin/user/'.$user->id) }}" method="POST" class="form-horizontal">
                                  <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                  <button id="deleteUser_{{ $user->id }}" data-what="Supprimer" data-action="{{ $user->name }}" class="btn btn-danger btn-xs deleteAction" type="submit">
                                      <span class="fa fa-exclamation-circle"></span> &nbsp;  Supprimer le compte
                                  </button>
                              </form>
                          </div>
                      </div>

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

                  </div>
                  <div class="col-md-8">

                      <!-- ADRESSES -->
                      <div class="panel-group" id="accordion">

                          @if(!$user->adresses->isEmpty())
                              @foreach ($user->adresses as $adresse)
                                  <div class="panel panel-midnightblue">
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-md-8">
                                                  <h3 style="margin-bottom: 0;">
                                                      <i class="fa fa-map-marker"></i>&nbsp;Adresse {{ $adresse->type_title }}
                                                      {!! $adresse->livraison ? '<small class="text-mute">livraison</small>' : '' !!}
                                                  </h3>
                                              </div>
                                              <div class="col-md-3 text-right">
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
                                              @include('backend.adresses.partials.update',['adresse' => $adresse])
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          @endif
                      </div>

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-table"></i> &nbsp;Inscriptions</h3>
                              @include('backend.users.partials.inscriptions')
                          </div>
                      </div>

                      @if(isset($user->inscription_groupes) && !$user->inscription_groupes->isEmpty())
                          <div class="panel panel-midnightblue">
                              <div class="panel-body">
                                  <h3><i class="fa fa-table"></i> &nbsp;Inscriptions groupés</h3>
                                  @include('backend.users.partials.groups')
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