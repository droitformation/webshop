@extends('sondages.layouts.master')
@section('content')

<h2>Inscriptions aux Déjeuners académiques</h2>

<h3>{!! Registry::get('conference.title')!!}</h3>
<h4>{{ frontendDate(Registry::get('conference.date')) }}</h4>
<div>{!! Registry::get('conference.comment')!!}</div>
<hr style="margin-top: 35px;">

@include('alert::bootstrap')

<div class="row">
    <div class="col-md-6 col-xs-12" style="border-right: 1px solid #eee; padding-right: 25px;">
        <h4>Inscription</h4>
        <form class="form-sondage" style="margin-top: 20px;" action="{{ url('conference') }}" method="POST">{!! csrf_field() !!}
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
    </div>
    <div class="col-md-6 col-xs-12" style="padding-left: 25px;">
        @if(!empty(Registry::get('academiques')))
            <h4 style="margin-bottom: 20px;">Personnes inscrites</h4>
            @foreach(Registry::get('academiques') as $index => $personne)

                <div class="row">
                    <div class="col-md-10">
                        <p><strong><i class="fa fa-caret-right"></i> &nbsp; {{ $personne['first_name'] }} {{ $personne['last_name'] }}</strong></p>
                    </div>
                    <div class="col-md-2 text-right">
                        <form action="{{ url('conference') }}" method="POST" class="form-horizontal">
                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                            <input type="hidden" name="index" value="{{ $index }}">
                            <button data-what="Supprimer" data-action="{{ $personne['first_name'] }} {{ $personne['last_name'] }}" class="btn btn-danger btn-xs deleteAction">x</button>
                        </form>
                    </div>
                </div>

            @endforeach
        @endif
    </div>
</div>

@stop