@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<article class="col-md-12">

			<p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>Résultats recherche {{ $term }}</h2>
				<span class="h-line"></span>
			</div>
			<?php $results = false; ?>
			<div class="row">
				<div class="col-md-6">
					@if(!$products->isEmpty())

						<h4>Livres</h4>

						@foreach($products as $product)
							<article class="row result-item">
								<a href="{{ url('pubdroit/product/'.$product->id) }}" class="col-md-2">
									<img width="60" class="thumbnail" src="{{ secure_asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
								</a>
								<div class="col-md-10">
									<h4><a href="{{ url('pubdroit/product/'.$product->id) }}">{{ $product->title }}</a></h4>
									<p>{{ strip_tags($product->teaser) }}</p>
								</div>
							</article>
						@endforeach
						<?php $results = true; ?>
					@endif
				</div>
				<div class="col-md-6">
					@if(!$colloques->isEmpty())

						<h4>Colloques</h4>

						@foreach($colloques as $colloque)
							<article class="row result-item">
								<a href="{{ url('pubdroit/colloque/'.$colloque->id) }}" class="col-md-2">
									@if(isset($colloque->illustration))
										<img width="60" class="thumbnail" src="{{ secure_asset('files/colloques/illustration/'.$colloque->illustration->path) }}" alt=""/>
									@endif
								</a>
								<div class="col-md-10">
									<h4><a href="{{ url('pubdroit/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a></h4>
									<p>{{ $colloque->soustitre }}</p>
									<p>{{ $colloque->sujet }}</p>
								</div>
							</article>
						@endforeach
						<?php $results = true; ?>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					@if(!$authors->isEmpty())

						<h4>Auteurs</h4>

						@foreach($authors as $author)
							<h3>{{ $author->name }}</h3>
							@if(!$author->products->isEmpty())
								@foreach($author->products as $product)
									<article class="row result-item">
										<a href="{{ url('pubdroit/product/'.$product->id) }}" class="col-md-2">
											<img width="60" class="thumbnail" src="{{ secure_asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
										</a>
										<div class="col-md-10">
											<h4><a href="{{ url('pubdroit/product/'.$product->id) }}">{{ $product->title }}</a></h4>
											<p>{{ strip_tags($product->teaser) }}</p>
										</div>
									</article>
								@endforeach
							@endif
						@endforeach
						<?php $results = true; ?>
					@endif

					{!! !$results ? '<p>Aucun résultat</p>' : '' !!}
				</div>
			</div>

		</article>
	</section>

@stop