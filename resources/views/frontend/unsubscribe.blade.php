@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-8 col-xs-12">

        <h1>Désinscription de la newsletter</h1>

        <hr/>

        <form action="{{ url('unsubscribe') }}" method="POST" class="form">
            {!! csrf_field() !!}
            <div class="form-group">
                <label class="control-label">Votre email</label>
                <div class="input-group col-md-7">
                    <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Se désinscrire!</button>
                    </span>
                </div><!-- /input-group -->
            </div>
            <input type="hidden" name="newsletter_id" value="{{ $id }}">
        </form>

    </div>

    <!-- Sidebar  -->
    <div class="col-md-4 col-xs-12">
        @include('partials.sidebar')
    </div>
    <!-- END Sidebar  -->
</div><!--END CONTENT-->

@stop

