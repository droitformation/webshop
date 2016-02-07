@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-6"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/duplicate') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
    <div class="col-md-6 text-right"><!-- col -->
        <div class="options">
            <div class="btn-toolbar">
                <form action="{{ url('admin/duplicate/'.$duplicate->id) }}" method="POST" class="form-horizontal">
                    <div class="btn-group">
                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                        <button data-what="Supprimer" data-action="{{ $duplicate->name }}" class="btn btn-danger deleteAction" type="submit">
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
    @if (!empty($duplicate))
           <div class="col-md-12"><!-- col -->

              <div class="row">
                  <div class="col-md-4">

                      <div class="panel panel-midnightblue">
                          <div class="panel-body">

                              <form action="{{ url('admin/duplicate/'.$duplicate->id) }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form">
                                  <input type="hidden" name="_method" value="PUT">
                                  {!! csrf_field() !!}
                                  <h3><i class="fa fa-duplicate"></i> &nbsp;Compte</h3>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Prénom</label>
                                      {!! Form::text('first_name', $duplicate->first_name , array('class' => 'form-control') ) !!}
                                  </div>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Nom</label>
                                      {!! Form::text('last_name', $duplicate->last_name , array('class' => 'form-control') ) !!}
                                  </div>
                                  <div class="form-group">
                                      <label for="message" class="control-label">Email</label>
                                      {!! Form::text('email', $duplicate->email , array('class' => 'form-control') ) !!}
                                  </div>
                                  
                                  <?php
                                  echo '<pre>';
                                  print_r($duplicate);
                                  echo '</pre>';
                                  ?>

                                  {!! Form::hidden('id', $duplicate->id ) !!}
                                  <button class="btn btn-primary pull-right" type="submit">Enregistrer</button>
                              </form>
                          </div>
                      </div>


                  </div>
                  <div class="col-md-8">

                      <!-- ADRESSES -->
                      <div class="panel-group" id="accordion">
                          @if(!$duplicate->adresses->isEmpty())
                              @foreach ($duplicate->adresses as $adresse)
                                  <div class="panel panel-midnightblue">
                                      <div class="panel-body">
                                          <h3 style="margin-bottom: 0;"><i class="fa fa-map-marker"></i>
                                              &nbsp;Adresse {{ $adresse->type_title }}  &nbsp;{!! $adresse->livraison ? '<small class="text-mute">livraison</small>' : '' !!}
                                              <a role="button" class="btn btn-sm btn-primary pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$adresse->id}}">Voir</a>
                                          </h3>
                                          <div id="collapse{{$adresse->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                              @include('backend.duplicates.adresse',['adresse' => $adresse])
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          @endif
                      </div>


                  </div>
              </div>

        </div>
    @endif
</div>
<!-- end row -->

@stop