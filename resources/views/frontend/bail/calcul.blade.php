@extends('layouts.bail.master')
@section('content')
		      				     
	 <!-- Illustration -->

	 <div id="content" class="inner"> 
	 	<div class="row">
			<p>Calculez les hausses et baisses de loyer en un clic</p>
    		         	
			{{ Form::open(array( 'action' => 'BailController@calcul', 'class' => 'large-4 columns')) }}						
				{{ Form::label('Votre canton', '' ) }}
				{{ Form::select('canton', $cantons) }}
				
				{{ Form::label('Votre loyer actuel (sans les charges)', '' ) }}
				{{ Form::text('loyer', '') }}
				
				{{ Form::label('Date d\'entrée en vigueur de votre loyer actuel', '' ) }}
				{{ Form::text('date', '', array('id' => 'datepicker')) }}
				
				{{ Form::submit('Envoyer', array('class' => 'button tiny colorBlock')) }}
			{{ Form::close() }}	

	 	</div>
	 </div>
@stop
