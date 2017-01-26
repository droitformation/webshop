@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<div class="col-md-12">

			<p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>{{ $product->title }}</h2>
				<span class="h-line"></span>
			</div>

			@if($product)
				<div class="row">

					<div class="col-md-3 col-xs-12">
						<div class="thumbnail">
							<img src="{{ asset('files/products/'.$product->image) }}" alt="">
						</div>
					</div>
					<div class="col-md-9 col-xs-12">

						<div class="row">
							@if(!$product->domains->isEmpty())
								<div class="col-md-6 col-xs-12">
									<p>
										@foreach($product->domains as $domain)
											<span class="label label-info">{{ $domain->title }}</span>
										@endforeach
									</p>
								</div>
							@endif
							<?php $attributs = $product->attributs->filter(function ($value, $key) {return in_array($value->id, [1,2]); }); ?>

							@if(!$attributs->isEmpty())
								<div class="col-md-6 col-xs-12">
									@foreach($attributs as $attribute)
										<p><strong>{{ $attribute->title }} : </strong> {{ $attribute->pivot->value }} </p>
									@endforeach
								</div>
							@endif
						</div>

						<h3>{{ $product->title }}</h3>

						<div>
							<p><strong>{!! $product->teaser !!}</strong></p>

							<div class="well well-sm product-description">
								{!!  $product->description !!}
							</div>

							@if(!$product->authors->isEmpty())
								<h4>Auteurs</h4>
								<ul>
									@foreach($product->authors as $author)
										<li>{{ $author->name }}</li>
									@endforeach
								</ul>
							@endif

							@if(!$product->categories->isEmpty())
								<h4>Catégories</h4>
								<ul>
									@foreach($product->categories as $categorie)
										<li>{{ $categorie->title }}</li>
									@endforeach
								</ul>
							@endif
						</div>

						<hr/>
						<!-- Product put in the basket button -->
						@include('frontend.pubdroit.partials.basket')
						<!-- END Product put in the basket button -->
					</div>

				</div>
			@endif

		</div>
	</section>

@stop