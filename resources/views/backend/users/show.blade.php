@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-6"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
    <div class="col-md-6 text-right"><!-- col -->
        <div class="options">
            <div class="btn-toolbar">
                <form action="{{ url('admin/user/'.$user->id) }}" method="POST" class="form-horizontal">
                    <div class="btn-group">
                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                        <button data-what="Supprimer" data-action="{{ $user->name }}" class="btn btn-danger deleteAction" type="submit">
                            <span class="fa fa-exclamation-circle"></span> &nbsp;  Supprimer le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                                  <h3><i class="fa fa-user"></i> &nbsp;Compte</h3>
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

                                  <a class="text-danger" data-toggle="collapse" href="#changePassword" href="#">
                                      <i class="fa fa-exclamation-circle"></i>&nbsp;Modifier le mot de passe
                                  </a>
                                  <div class="collapse" id="changePassword">
                                      <div class="form-group">
                                          <br/>
                                          <label for="pasword" class="control-label">Nouveau mot de passe</label>
                                          <input type="password" name="password" class="form-control">
                                      </div>
                                  </div>

                                  {!! Form::hidden('id', $user->id ) !!}
                                  <button class="btn btn-primary pull-right" type="submit">Enregistrer</button>
                              </form>
                          </div>
                      </div>

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-tags"></i> &nbsp;Spécialisations</h3>
                              <ul id="specialisations" data-model="adresse" data-id="{{ $user->adresse_facturation->id }}">
                                  @if(isset($user->adresse_facturation) && !$user->adresse_facturation->specialisations->isEmpty())
                                      @foreach($user->adresse_facturation->specialisations as $specialisation)
                                          <li>{{ $specialisation->title }}</li>
                                      @endforeach
                                  @endif
                              </ul>
                              <hr/>
                              <h3><i class="fa fa-bookmark"></i> &nbsp;Membre</h3>
                              <ul id="members" data-id="{{ $user->adresse_facturation->id }}">
                                  @if(isset($user->adresse_facturation) && !$user->adresse_facturation->specialisations->isEmpty())
                                      @foreach($user->adresse_facturation->members as $members)
                                          <li>{{ $members->title }}</li>
                                      @endforeach
                                  @endif
                              </ul>
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
                                          <h3 style="margin-bottom: 0;"><i class="fa fa-map-marker"></i>
                                              &nbsp;Adresse {{ $adresse->type_title }}  &nbsp;{!! $adresse->livraison ? '<small class="text-mute">livraison</small>' : '' !!}
                                              <a role="button" class="btn btn-sm btn-primary pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$adresse->type}}">Voir</a>
                                          </h3>
                                          <div id="collapse{{$adresse->type}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                              @include('backend.users.adresse',['adresse' => $adresse])
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

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes</h3>

                              @include('backend.users.partials.commandes')
                          </div>
                      </div>

                  </div>
              </div>

        </div>
    @endif
</div>
<!-- end row -->

@stop