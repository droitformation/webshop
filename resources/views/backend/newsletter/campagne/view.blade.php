@extends('newsletter.layouts.newsletter')
@section('content')

    @if(!empty($content))
        @foreach($content as $bloc)
            <?php  echo view('newsletter/send/'.$bloc->type->partial)->with(array('bloc' => $bloc))->__toString(); ?>
        @endforeach
    @endif

@stop