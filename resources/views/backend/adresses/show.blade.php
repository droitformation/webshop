@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-6"><!-- col -->
        <p><a class="btn btn-default" href="{{ redirect()->getUrlGenerator()->previous() }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
    <div class="col-md-6 text-right"><!-- col -->
        <div class="options">
            <div class="btn-toolbar">
                <form action="{{ url('admin/adresse/'.$adresse->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                    <input type="hidden" name="url" value="{{ redirect()->getUrlGenerator()->previous() }}">
                    <div class="btn-group">
                        @if($adresse->user_id == 0 || !$adresse->user_id)
                            <button class="btn btn-warning pull-right" type="button" data-toggle="collapse" data-target="#collapseConvert" aria-expanded="false" aria-controls="collapseConvert">
                                Convertir en compte utilisateur
                            </button>
                        @else
                            <a class="btn btn-info" href="{{ url('admin/user/'.$adresse->user_id) }}"><i class="fa fa-user"></i> &nbsp;Voir le compte</a>
                        @endif
                        <button data-what="Supprimer" data-action="{{ $adresse->name }}" class="btn btn-danger deleteAction" type="submit">
                            <span class="fa fa-exclamation-circle"></span> &nbsp;  Supprimer l'adresse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- start row -->
<div class="row">
    @if (!empty($adresse))
           <div class="col-md-12"><!-- col -->

              <div class="row">
                  <div class="col-md-7">
                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3 style="margin-bottom: 0;"><i class="fa fa-map-marker"></i> &nbsp;Adresse {{ $adresse->type_title }}</h3>
                              @include('backend.adresses.partials.update',['adresse' => $adresse])
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">

                      <div class="collapse" id="collapseConvert">
                          <div class="panel panel-warning">
                              <div class="panel-body">
                                  <form action="{{ url('admin/adresse/convert') }}" method="POST">{!! csrf_field() !!}
                                      <div class="form-group">
                                          <label><strong>Indiquer un mot de passe</strong></label>
                                          <div class="input-group">
                                              <input type="password" class="form-control" required autocomplete="off" placeholder="Mot de passe">
                                              <input type="hidden" value="{{ $adresse->id }}" name="id">
                                              <span class="input-group-btn">
                                                <button class="btn btn-warning" type="submit">Convertir!</button>
                                              </span>
                                          </div><!-- /input-group -->
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3><i class="fa fa-tags"></i> &nbsp;Spécialisations</h3>
                              <ul id="specialisations" data-model="adresse" data-id="{{ $adresse->id }}">
                                  @if(!$adresse->specialisations->isEmpty())
                                      @foreach($adresse->specialisations as $specialisation)
                                          <li>{{ $specialisation->title }}</li>
                                      @endforeach
                                  @endif
                              </ul>
                              <hr/>
                              <h3><i class="fa fa-bookmark"></i> &nbsp;Membre</h3>
                              <ul id="members" data-id="{{ $adresse->id }}">
                                  @if(!$adresse->specialisations->isEmpty())
                                      @foreach($adresse->members as $members)
                                          <li>{{ $members->title }}</li>
                                      @endforeach
                                  @endif
                              </ul>
                          </div>
                      </div>

                  </div>
              </div>

        </div>
    @endif
</div>
<!-- end row -->

@stop