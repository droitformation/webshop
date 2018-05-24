@extends('frontend.matrimonial.layouts.master')
@section('content')

	<!-- Illustration -->
	<section>
		<img src="{{ secure_asset('/images/matrimonial/home.jpg') }}" alt="">
	</section>

	<div id="content" class="inner inner-app">

        <div class="row">
            <div class="col-md-12 homepageBlock">

                @if(isset($page))

                    <div class="row">
                        <div class="col-md-9">
                            <h1>{{ $page->title }}</h1>
                            {!! $page->content !!}
                        </div>
                        <div class="col-md-3">
                            <div class="sidebar-app">
                                @if(isset($page) && !$page->blocs->isEmpty())
                                    @foreach($page->blocs as $bloc)
                                        @if($bloc->type == 'soutien')
                                            <div class="widget clear">
                                                @include('frontend.partials.bloc', ['bloc' => $bloc])
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!$page->contents->isEmpty())
                        <h4 class="line">News</h4>
                        <?php $chunk = $page->contents->chunk(3); ?>
                        @foreach($chunk as $contents)
                            <div class="row">
                                @foreach($contents as $content)
                                    <div class="col-md-4 homepageBlock">
                                        <div class="{{ !empty($content->style) ? $content->style : '' }}">
                                            <h5>{!! $content->title !!}</h5>
                                            {!! $content->content !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @endforeach
			        @endif
		        @endif
            </div>
        </div>

	</div>

@stop
