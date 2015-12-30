@extends('layouts.bail.master')
@section('content')
	
	<?php  $custom = new Custom; ?>	      				     
	 <!-- Illustration -->

	 <div id="content" class="inner">
	 
	 	 <div class="row">
	 	 	<div class="large-12 columns">
		 	 	<h5 class="line">Les contributions aux séminaires</h5>
	 	 	</div>
	 	 </div>
	 	 	 	 	
	 	 <div class="row">
	 	 	<div id="seminaires" class="large-12 columns">
	 	 		<div class="sujets">
				
					<div class="catTitle clear">
						<div class="category col"><strong>Catégorie</strong></div>
						<div class="edition col"><strong>Edition du séminaire</strong></div>
						<div class="annee col"><strong>Année</strong></div>
						<div class="desc col"><strong>Description</strong></div>
						<div class="auteur col"><strong>Auteur</strong></div>
						<div class="order col">&nbsp;</div>
					</div>
					
					<?php $custom->knatsort($categories);  ?>
			
					<div class="cat clear ">
						<div class="liste">
														
							@foreach($categories as $title => $subjects)													
								@foreach($subjects as $subject)
								
									<?php 
										
										/* =================================
										  Infos for filter
										  categorie id
										  Author id
										  Seminaire id
										=================================== */
									 
										$subjects_seminaires = $subject->subjects_seminaires->toArray();
										
										$title_seminaire = '';
										$year_seminaire  = '';
										$order_link      = '';
										
										if( isset($subjects_seminaires[0]) )
										{
											$title_seminaire = $subjects_seminaires[0]['title'];
											$year_seminaire  = $subjects_seminaires[0]['year'];
											$order_link      = $subjects_seminaires[0]['orderlink'];
											$seminaire_id    = $subjects_seminaires[0]['id'];
										}
										
										$author_id    = $subject->subjects_authors->first()->id;	
										$categorie_id = $subject->subjects_categories->first()->id;									
										
									 ?>
	
									<div class="sujet clear c<?php echo $categorie_id; ?> y<?php echo $seminaire_id; ?> a<?php echo $author_id; ?> ">
										<div class="category col">
											<h4> {{ $title }} </h4>
										</div>

										<div class="edition col"><?php echo $title_seminaire; ?></div>
										<div class="annee col"><?php   echo $year_seminaire; ?></div>										
										<div class="desc col"><?php    echo $subject->title; ?></div>
										<div class="auteur col"><?php  echo $subject->subjects_authors->first()->name; ?></div>
										<div class="order col">
										
											@if( $order_link )
												<a class="order" target="_blank" href="{{ $order_link }}">Acquérir</a>
											@endif
											
											@if( $subject->file )
												<a class="order" target="_blank" href="{{ $subject->file }}">Télécharger</a>
											@endif
											
											@if( $subject->appendixes )
											
												<?php
												
													$list_appendixes = explode(',',$subject->appendixes);
													
													if($list_appendixes > 1)
													{
														$i = 1;
														foreach($list_appendixes as $appendixe)
														{
															echo '<a class="order" target="_blank" href="'.$appendixe.'">Annexe '.$i.'</a>';
															$i++;
														}
													}
													else
													{
														echo '<a class="order" target="_blank" href="'.$subject->appendixes.'">Annexe 1</a>';
													}
												?>
												
											@endif
																							
										</div>
									</div>
								
								@endforeach										
							@endforeach
						
						</div>
					</div>
	
				</div><!-- end sujets div -->
				
	 	 	</div>	 	 	
	 	 </div>
	 	 
	 </div>
@stop
