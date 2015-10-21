@extends('backend.newsletter.layouts.newsletter')
@section('content')

    @if(!empty($content))
        @foreach($content as $bloc)
            {!! view('backend/newsletter/send/'.$bloc->type->partial)->with(['bloc' => $bloc,'categories' => $categories, 'imgcategories' => $imgcategories]) !!}
        @endforeach
    @endif

@stop