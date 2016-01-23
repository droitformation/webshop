@extends('layouts.bail.master')
@section('content')
		      				     
	<div id="content" class="inner">
	 	 <div class="row">
	 	 	<div class="col-md-12">
                <h3 class="line up">{{ $page->title }}</h3>

				@if(!$faqcats->isEmpty())
					<div class="faq_list">
						@foreach($faqcats as $categorie)
							<p><a class="{{ $current == $categorie->id ? 'active' : '' }}" href="{{ url('bail/page/faq/'.$categorie->id) }}">{{ $categorie->title }}</a></p>
						@endforeach
						<div class="clearfix"></div>
					</div>
				@endif

	 	 	</div>
	 	 </div>
		<div class="row">
			<div class="col-md-12">
				 @if(!$questions->isEmpty())
					 @foreach($questions as $question)

						 <div class="faq_wrapper">
							 <h3>{!! $question->title !!}</h3>
							 <div>
								 <cite>{!! $question->question !!}</cite>
							 	 {!! $question->answer !!}
							 </div>
						 </div>

					 @endforeach
				 @endif
			</div>
		</div>

	 </div>
@stop
