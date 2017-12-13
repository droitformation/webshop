@extends('access.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-6"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('access') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
    <div class="col-md-6 text-right"><!-- col -->
        <div class="options">
            <div class="btn-toolbar">
                <form action="{{ url('access/adresse/'.$adresse->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                    <input type="hidden" name="url" value="{{ url('access')}}">

                    @if(!session()->has('term'))
                        <input type="hidden" name="term" value="{{ session()->get('term') }}">
                    @endif

                    <div class="btn-group">
                        <button data-what="Supprimer" data-action="{{ $adresse->name }}" class="btn btn-danger deleteAction" type="submit">
                            <span class="fa fa-exclamation-circle"></span> &nbsp;Supprimer l'adresse
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
           <div class="col-md-8"><!-- col -->

              <div class="row">
                  <div class="col-md-12">
                      <div class="panel panel-midnightblue">
                          <div class="panel-body">
                              <h3 style="margin-bottom: 0;"><i class="fa fa-map-marker"></i> &nbsp;Adresse {{ $adresse->type_title }}</h3>
                              @include('backend.adresses.partials.update',['adresse' => $adresse, 'path' => 'access'])
                          </div>
                      </div>
                  </div>
              </div>

        </div>
        <div class="col-md-4">

            @if($adresse->type == 1)
                <div class="panel panel-midnightblue">
                    <div class="panel-body">
                        <h3>Vos listes</h3>
                        @foreach(\Auth::user()->access as $specialisation)
                            <p><strong>{{ $specialisation->title }}</strong></p>
                        @endforeach
                        <hr/>
                        <p><strong>&nbspSupprimer l'adresse de la liste</strong></p>
                        <?php
                            $specialisations_adresse = $adresse->specialisations->filter(function ($value, $key) {
                                return \Auth::user()->access->contains('id',$value->id);
                            })->reduce(function ($carry, $item) {
                                return $carry.'<li>'.$item->title.'</li>';
                            }, '');
                        ?>
                        @if(!empty($specialisations_adresse))
                            <ul id="specialisations" data-model="adresse" data-tags="{{ json_encode(\Auth::user()->access->pluck('title')) }}" data-id="{{ $adresse->id }}">
                                {!! $specialisations_adresse !!}
                            </ul>
                        @endif
                        <hr/>
                        <p class="text-muted">Si le tag de la liste est supprimé sans le vouloir,
                            il possible de le remettre en choisissant le tag correspondant dans les spécialisations.</p>
                        <p class="text-warning">Il n'est possible d'ajouter un tag que des listes qui vous sont assignés.</p>
                    </div>

                </div>
            @endif

        </div>
    @endif
</div>
<!-- end row -->

@stop