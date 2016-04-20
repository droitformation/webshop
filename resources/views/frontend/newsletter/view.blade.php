@extends('backend.newsletter.layouts.newsletter')
@section('content')

    @if(!empty($content))
        @foreach($content as $bloc)
            <?php echo view('backend/newsletter/send/'.$bloc->type->partial)->with(['bloc' => $bloc, 'infos' => $infos,'categories' => $categories, 'imgcategories' => $imgcategories])->__toString(); ?>
        @endforeach
    @endif

@stop