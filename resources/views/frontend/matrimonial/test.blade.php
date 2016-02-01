@extends('frontend.matrimonial.layouts.master')
@section('content')
	
	 <div id="content" class="inner">
	 
	 	 <div class="row">
	 	 	<div class="large-12 columns">
		 	 	<h5 class="line">Jurisprudence</h5>
	 	 	</div>
	 	 </div>
	 	 
	 	 <div id="arrets" class="row">		 	  	 
		 	 <div class="arrets">
		 	 
		 	 	<?php
		 	 	
		 	 	$queries = DB::getQueryLog();
		 	 	$last_query = end($queries);
					
					foreach($arrets as $analyse)
					{
						$arrets_analyses  = $analyse->arrets_analyses->toArray();
						$categories_analy = $analyse->analyses_categories->toArray(); 
					
						echo '<pre>';
						print_r($arrets_analyses);
						echo '</pre>';
						
					}
									
				?>
					 			
			</div>			
	 	 </div>		 
	 	 
	</div>
		
@stop
