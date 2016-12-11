@extends('frontend.bail.layouts.master')
@section('content')

	 <div id="content" class="inner inner-app">
		 <div class="row">
			 <div class="col-md-8">
				 <h3 class="line up">Résultats</h3>
		 	 	 @if( !empty($resultats) )
		 	 		{{ print_r($resultats) }}
		 	 	 @endif
	 	 	</div>
			 <div class="col-md-4">
				 @include('frontend.bail.partials.sidebar')
			 </div>
	 	 </div>
	 </div>

@stop
