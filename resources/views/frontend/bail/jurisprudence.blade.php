@extends('layouts.bail.master')
@section('content')
		      				     
	 <!-- Illustration -->

	 <div id="content" class="inner">
	 
	 	 <div class="row">
	 	 	<div class="large-12 columns">
		 	 	<h5 class="line">Jurisprudence</h5>
	 	 	</div>
	 	 </div>
	 	 
	 	 <div id="arrets" class="row">	 	 
		 	 <div class="arrets">
	
			 	 <!-- liste des analyses -->
			 	 
			 	 <div id="analyses" class="cat clear analyse">
			 	 
					<div class="details large-3 columns">
						<img src="{{ asset('/images/bail/categories/analyse.jpg') }}" alt="" width="140" height="140" />
						<h4>Analyse</h4>
					</div>
					
					<div class="liste large-9 columns">
					
					@if( !empty($analyses) )
					
						@foreach($analyses as $analyse)
							<a name="analyse-{{ $analyse->id }}"></a>
								
								<?php 
								
									$allcat = ''; 
									$anacat = $analyse->analyses_categories->toArray();	
									$anarr  = $analyse->arrets_analyses;	
									setlocale(LC_ALL, 'fr_FR'); 	
								?>
	
								@foreach($anacat as $cats)					
									<?php  $allcat .= 'c'.$cats['pivot']['categorie_id'].' '; ?>				
								@endforeach
								
								<div class="arret analyse c{{ $analyse->categories }} {{ $allcat }}">
									<h3>Analyse de {{ $analyse->authors }}</h3>
									<ul class="liste-arrets arrets-internal-links">
										@if(!empty($anarr))
											@foreach($anarr as $arr)
												<li>{{ link_to('bail/jurisprudence#a-'.$arr['id'] , $arr->reference ) }} du {{ $arr->pub_date->formatLocalized('%e %B %Y') }}</li>
											@endforeach 
										@endif
									</ul>							
									<p class="abstract">{{ $analyse->abstract }}</p>
	
									@if( !empty($analyse->file) )
									<p><a href="uploads/tx_bailarrets/{{$analyse['file']}}" target="_blank">Télécharger cette analyse en PDF</a></p>
									@endif
								</div>
								
						@endforeach 
					@endif
					</div>
				 </div>
			 
			 	 <!-- fin liste des analyses -->
			 	 
				 <!-- liste des arrets -->
				 <div class="cat clear bail">
						<div class="liste">
						
						@if(!empty($arrets))
							@foreach($arrets as $arret)
								<a name="a-{{ $arret->id }}"></a>
								
									<?php 
										$allarrcat        = '';									
										$categories_arret = $arret->arrets_categories->toArray(); 
										$arrets_analyses  = $arret->arrets_analyses->toArray(); 
										setlocale(LC_ALL, 'fr_FR');  
									?>
									
									@foreach($categories_arret as $arcats)					
										<?php  $allarrcat .= 'c'.$arcats['id'].' '; ?>				
									@endforeach						
									
								<div class="arret {{ $allarrcat }} y{{ $arret->pub_date->year }} clear">
									<div class="categories large-3 columns">							
									@foreach($categories_arret as $cat)							
										<div class="details">					
											<img src="{{ asset('/images/bail/categories/'.$cat['image']) }}" alt="{{ $cat['title'] }}" width="140" height="140" />
											<h4><?php print_r($cat['title']); ?> </h4>
										</div>						
									@endforeach
									</div>
									<div class="content large-9 columns">
										<h3>{{ $arret->reference }} du {{ $arret->pub_date->formatLocalized('%e %B %Y') }}</h3>
										<p class="abstract">{{ $arret->abstract }}	</p>
										{{ $arret->pub_text }}
									
										@if(!empty( $arret->file ))
											<p><a href="uploads/tx_bailarrets/{{ $arret->file }}" target="_blank">Télécharger en PDF</a></p>
										@endif
										
										<ul class="liste-analyses arrets-internal-links">
										@foreach($arrets_analyses as $analyse)
											<li>{{ link_to('bail/jurisprudence#analyse-'.$analyse['id'], 'Analyse de '.$analyse['authors'] ) }}</li>
										@endforeach 
										</ul>
										
									</div>
								</div>
								
							@endforeach 
						@endif
						
						</div>
					</div>
					<!-- fin liste des arrets -->
	
			</div>
	 	 </div>		 
	 	 
	 </div>
@stop
