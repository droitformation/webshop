@extends('layouts.master')
@section('content')

<div class="row">
    <div id="inner-content" class="col-md-8 col-xs-12">

        <h2>Newsletter</h2>
        <h3>{{ $newsletter->titre }}</h3>

        <hr/>

        @if(!$newsletter->campagnes->isEmpty())
            <ul class="list-group">
                @foreach($newsletter->campagnes as $campagne)
                    @if($campagne->status == 'envoyé')
                        <a href="{{ url('newsletter/campagne/'.$campagne->id) }}" class="list-group-item">{{ $campagne->sujet }}</a>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Sidebar  -->
    <div id="sidebar" class="col-md-4 col-xs-12">
        @include('partials.subscribe')
    </div>
    <!-- END Sidebar  -->

</div><!--END CONTENT-->

@stop
