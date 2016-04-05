@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<article class="col-md-12">

			<p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>Résultats recherche {{ $term }}</h2>
				<span class="h-line"></span>
			</div>

			<div class="row">
				<div class="col-md-6">
					@if(!$products->isEmpty())

						<h4>Livres</h4>

						@foreach($products as $product)
							<article class="row result-item">
								<a href="{{ url('product/'.$product->id) }}" class="col-md-2">
									<img width="60" class="thumbnail" src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
								</a>
								<div class="col-md-10">
									<h4><a href="{{ url('product/'.$product->id) }}">{{ $product->title }}</a></h4>
									<p>{{ strip_tags($product->teaser) }}</p>
								</div>
							</article>
						@endforeach

					@endif
				</div>
				<div class="col-md-6">
					@if(!$colloques->isEmpty())

						<h4>Colloques</h4>

						@foreach($colloques as $colloque)
							<article class="row result-item">
								<a href="{{ url('colloque/'.$colloque->id) }}" class="col-md-2">
									<img width="60" class="thumbnail" src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" alt=""/>
								</a>
								<div class="col-md-10">
									<h4><a href="{{ url('colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a></h4>
									<p>{{ $colloque->soustitre }}</p>
									<p>{{ $colloque->sujet }}</p>
								</div>
							</article>
						@endforeach
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					@if(!$authors->isEmpty())

						<h4>Auteurs</h4>

						@foreach($authors as $author)
							<article class="row result-item">
								<a href="{{ url('colloque/'.$colloque->id) }}" class="col-md-2">
									<img width="60" class="thumbnail" src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" alt=""/>
								</a>
								<div class="col-md-10">
									<h4><a href="{{ url('colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a></h4>
									<p>{{ $colloque->soustitre }}</p>
									<p>{{ $colloque->sujet }}</p>
								</div>
							</article>
						@endforeach
					@endif

				</div>
			</div>

		</article>
	</section>

@stop