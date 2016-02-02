@extends('frontend.bail.layouts.master')
@section('content')
		      				     
	 <!-- Illustration -->

	 <div id="content" class="inner">
	 
	 	 <div class="row">
	 	 	<div class="col-md-12">
		 	 	<h3 class="line up">Jurisprudence</h3>
	 	 	</div>
	 	 </div>
	 	 <div id="arrets" class="row">
			 <div id="filtering" class="col-md-12">
				 <div class="arrets">
	
                     <!-- liste des analyses -->

                    @if(!$analyses->isEmpty())
                        @include('frontend.bail.partials.analyse')
                    @endif

                     <!-- fin liste des analyses -->

                     <!-- liste des arrets -->
                     <div class="cat clear bail">
						<div class="liste">

						@if(!$arrets->isEmpty())
							@foreach($arrets as $arret)
								@include('frontend.bail.partials.arret')
							@endforeach
						@endif

						</div>
					</div>
					<!-- fin liste des arrets -->
				 </div>
			</div>
	 	 </div>		 
	 	 
	 </div>
@stop
