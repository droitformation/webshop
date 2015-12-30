@extends('layouts.matrimonial')

@section('content')

<?php use Carbon\Carbon; ?>
		      				     
<div id="content" class="inner">

	 	 <div id="arrets" class="row">		 	  	 
		 	 <div class="arrets">
			 	
			 	 <div class="cat clear">
	
		 	 		 <div class="title clear">
						 <img src="<?php echo asset('/images/matrimonial/categories/analyse_02.jpg'); ?>" alt="" width="140" height="140" />
						 <h4>Analyses</h4>						
					  </div>
					  <div class="liste">	
		 	  		  
		 	  		  @if(!empty($analyses))							
						@foreach($analyses as $analyse)
						
					 		 <a name="analyse-{{ $analyse['id'] }}"></a>
					 		 
								<?php 
								
									$allancat         = '';									
									$arrets_analyses  = $analyse->arrets_analyses;
									$categories_analy = $analyse->analyses_categories->toArray();  
									setlocale(LC_ALL, 'fr_FR');  

								?>
								
								@foreach($categories_analy as $arcats)					
									<?php  $allancat .= 'c'.$arcats['id'].' '; ?>				
								@endforeach	
								
								<div class="arret analyse {{ $allancat }} y{{ $analyse->pub_date->year }} clear row">

									<div class="content">
										
										<h3>Analyse de <?php echo $analyse->authors; ?></h3>
											
										<ul class="liste-arrets arrets-internal-links">
										@if(!empty($arrets_analyses))
											@foreach($arrets_analyses as $arret_an)
											<li>{{ link_to('matrimonial/jurisprudence#a-'.$arret_an->id,$arret_an->reference) }} du {{ $arret_an->pub_date->formatLocalized('%e %B %Y') }}</li>
											@endforeach
										@endif
										</ul>
										<p class="abstract"><?php echo $analyse->abstract; ?></p>
										
										@if( !empty($analyse->file) )
											<p><a href="uploads/tx_bailarrets/{{$analyse['file']}}" target="_blank">Télécharger cette analyse en PDF</a></p>
										@endif
											
									</div>
								</div>	
									
		 	 				@endforeach
		 	 			@endif
						</div>
										
			 	 </div>
			 	 
			 	 <div class="cat clear">
			 	 
			 	 	@if(!empty($arrets))							
						@foreach($arrets as $idcat => $arr)
			 	 
						  <div class="title clear">
							 <img src="<?php echo asset('/images/matrimonial/categories/'.$categories[$idcat]['image']); ?>" alt="" width="140" height="140" />
							 <h4><?php echo $categories[$idcat]['title'];  ?></h4>						
						  </div>
						  <div class="liste">						 
						 	  									
								@if(!empty($arr))
									@foreach($arr as $arret)
									
										<a name="a-{{ $arret['id'] }}"></a>
										
											<?php 
											
												$allarrcat        = '';									
												$categories_arret = $arret['arrets_categories']; 
												$arrets_analyses  = $arret['arrets_analyses']; 
												setlocale(LC_ALL, 'fr_FR');  
												
												$date = $arret['pub_date'];
												
												$instance   = Carbon::createFromTimeStamp($date);
												$formatDate = $instance->formatLocalized('%e %B %Y');
												$year       = $instance->year;
											?>
											
											@foreach($categories_arret as $arcats)					
												<?php  $allarrcat .= 'c'.$arcats['id'].' '; ?>				
											@endforeach						
											
										<div class="arret {{ $allarrcat }} y{{ $year }} clear row">
											<div class="categories large-3 columns">							
											@foreach($categories_arret as $cat)							
												<div class="details">	
													@if($cat['ismain'] != 1)				
														<img src="{{ asset('/images/matrimonial/categories/'.$cat['image']) }}" alt="{{ $cat['title'] }}" width="100" height="100" />
														<h4><?php print_r($cat['title']); ?> </h4>
													@endif
												</div>						
											@endforeach
											</div>
											<div class="content large-9 columns">
												<h3>{{ $arret['reference'] }} du {{ $formatDate }}</h3>
												<p class="abstract">{{ $arret['abstract'] }}</p>
												{{ $arret['pub_text'] }}
											
												@if(!empty( $arret['file'] ))
													<p><a href="uploads/tx_matrimonialarrets/{{ $arret['file'] }}" target="_blank">Télécharger en PDF</a></p>
												@endif
												
												<ul class="liste-analyses arrets-internal-links">
													@foreach($arrets_analyses as $analyse)
														<li>{{ link_to('matrimonial/jurisprudence#analyse-'.$analyse['id'], 'Analyse de '.$analyse['authors'] ) }}</li>
													@endforeach 
												</ul>												
											</div>
										</div>
										
									@endforeach 
								@endif
						  </div>
						@endforeach
					@endif
					
				 </div>			
			
		</div>			
 	 </div>		 
 	 
</div>

@stop
