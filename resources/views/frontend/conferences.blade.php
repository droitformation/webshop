@extends('sondages.layouts.master')
@section('content')

    <h2>Inscriptions aux Déjeuners académiques</h2>

    @if(\Registry::get('conference') !== null)

        <h3>{!! Registry::get('conference.title') !!}</h3>
        <h4>{{ frontendDate(Registry::get('conference.date')) }}</h4>
        <div>{!! Registry::get('conference.comment')!!}</div>

        <hr style="margin-top: 35px;">

        @include('flash::message')

        <div class="row">
            <div class="col-md-6 col-xs-12 col-md-push-3">
                <h4>Inscription</h4>
                @if($isOpen)
                    <form class="form-sondage" style="margin-top: 20px;" action="{{ url('dejeuner') }}" method="POST">{!! csrf_field() !!}
                        <div class="form-group">
                            <label><strong>Prénom</strong></label>
                            <input type="text" class="form-control" required name="first_name" value="{{ old('first_name') }}">
                        </div>
                        <div class="form-group">
                            <label><strong>Nom</strong></label>
                            <input type="text" class="form-control" required name="last_name" value="{{ old('last_name') }}">
                        </div>
                        <div class="form-group">
                            <label><strong>Email</strong></label>
                            <input type="email" class="form-control" required name="email" value="{{ old('email') }}">
                        </div>
                        <p class="text-right"><button type="submit" class="btn btn-primary">Envoyer &nbsp; <i class="fa fa-arrow-circle-o-right"></i></button></p>
                    </form>
                @else
                    <p class="text-danger">L'événement est complet</p>
                @endif
            </div>

        </div>

    @else
        <h3>Encore aucun déjeuner de prévu</h3>
    @endif

@stop