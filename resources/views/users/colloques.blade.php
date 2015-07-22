@extends('layouts.master')
@section('content')

    <div class="container-fluid main-container">

        @include('users.partials.nav')

        <div class="col-md-9 content">

            <div class="panel panel-default">
                <div class="panel-heading">Vos inscriptions</div>
                <div class="panel-body">

                    @if(!$user->inscriptions->isEmpty())
                        @foreach($user->inscriptions as $inscription)
                            <a href="{{ url('profil/inscription/'.$inscription->id) }}">{{ $inscription->colloque->titre }}</a>
                        @endforeach
                    @endif

                </div><!-- end panel body -->
            </div><!-- end panel -->

        </div>
    </div>

@endsection
