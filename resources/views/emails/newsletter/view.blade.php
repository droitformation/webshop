@extends('emails.newsletter.layouts.master')
@section('content')

    <?php setlocale(LC_TIME, 'fr_CH.UTF-8'); ?>

    @if(!$campagne->content->isEmpty())
        @foreach($campagne->content as $bloc)
            {!! view('emails.newsletter.send.'.$bloc->type->partial)
                ->with(['bloc' => $bloc, 'arret' => (isset($bloc->arret) ? $bloc->arret : null) ,'campagne' => $campagne])->__toString() !!}
        @endforeach
    @endif

@stop