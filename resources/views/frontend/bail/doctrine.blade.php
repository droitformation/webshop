@extends('frontend.bail.layouts.master')
@section('content')

    <div id="content" class="inner inner-app">
        <div class="row">
            <div class="col-md-8">

                <h3 class="line up">{{ $page->title }}</h3>
                {!! $page->content !!}

                @if(!$doctrines->isEmpty() && !empty($order))
                    <div id="seminaires">
                        <div class="sujets">
                            <div class="row titleRow">
                                <div class="col-md-2"><strong>Catégorie</strong></div>
                                <div class="col-md-3"><strong>Edition du séminaire </strong></div>
                                <div class="col-md-1"><strong>Année</strong></div>
                                <div class="col-md-3"><strong>Description</strong></div>
                                <div class="col-md-2"><strong>Auteur</strong></div>
                                <div class="col-md-1"><strong>Lien</strong></div>
                            </div>

                            @foreach($order as $key => $cat)
                                <div class="cat clear"><!-- start cat -->
                                    <?php $group = $doctrines->pull($cat); ?>
                                    @if($group)
                                        @foreach($group as $row)
                                            <div class="sujet clear c{{ $key }} {{ $row->seminaire ? 'y'.$row->seminaire->year : '' }} {{ $row->authors_list }}">
                                                <div class="row">
                                                    <div class="col-md-2"><i>{{ ucfirst($cat) }}</i></div>
                                                    <div class="col-md-2">{{ $row->seminaire ? $row->seminaire->title : '' }}</div>
                                                    <div class="col-md-1">{{ $row->seminaire ? $row->seminaire->year : '' }}</div>
                                                    <div class="col-md-3">{{ $row->title }}</div>
                                                    <div class="col-md-2">{{ $row->authors->implode('name', ', ') }}</div>
                                                    <div class="col-md-2">
                                                        @if($row->file_path)
                                                            <p><a target="_blank" href="{{ secure_asset($row->file_path) }}">Télécharger</a></p>
                                                        @endif

                                                        @if($row->annexe_path)
                                                            @foreach($row->annexe_path as $annexe)
                                                                <p><a target="_blank" href="{{ secure_asset($annexe['link']) }}">Télécharger</a></p>
                                                            @endforeach
                                                        @endif

                                                        @if(isset($row->seminaire) && ($row->seminaire->year > 2007) && isset($row->seminaire->product))
                                                            <p><a target="_blank" href="{{ secure_url('http://publications-droit.ch/pubdroit/product/'.$row->seminaire->product->id) }}">Ouvrage</a></p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                @include('frontend.bail.partials.sidebar')
            </div>
        </div>
    </div>

@stop
