@extends('layouts.bail.master')
@section('content')
		      				     
	 <!-- Illustration -->

	 <div id="content" class="inner">
	 	 <div class="row">
	 	 	<div class="large-12 columns">
		 	 	<h5 class="line">Résultats</h5>
		 	 	@if( !empty($resultats) )
		 	 		{{ print_r($resultats) }}
		 	 	@endif
	 	 	</div>
	 	 </div>
	 </div>
@stop
