@extends('frontend.newsletter.layouts.print')
@section('content')

    <div class="header">
        {!! $campagne->newsletter->site ? '<h1>'.$campagne->newsletter->site->nom.'</h1>' : '' !!}
        <h2>{{ $campagne->sujet }}</h2>
        <h3>{{ $campagne->auteurs }}</h3>
    </div>

    <?php setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>

    @if(!$campagne->content->isEmpty())
        @foreach($campagne->content as $bloc)
            {!! view('frontend.newsletter.print.'.$bloc->type->partial)->with(['bloc' => $bloc , 'campagne' => $campagne])->__toString() !!}
        @endforeach
    @endif

@stop