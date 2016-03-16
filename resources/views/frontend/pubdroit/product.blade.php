@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<div class="col-md-12">

			<p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

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
							<div class="col-md-6 col-xs-12">
								<p>
									@if(!$product->domains->isEmpty())
										@foreach($product->domains as $domain)
											<span class="label label-info">{{ $domain->title }}</span>
										@endforeach
									@endif
								</p>
							</div>
							<div class="col-md-6 col-xs-12">
								<?php $attributs = $product->attributs->filter(function ($value, $key) {return in_array($value->id, [1,2]); }); ?>

								@if(!$attributs->isEmpty())
									@foreach($attributs as $attribute)
										<p><strong>{{ $attribute->title }} :</strong>{{ $attribute->pivot->value }} </p>
									@endforeach
								@endif
							</div>
						</div>

						<h3>{{ $product->title }}</h3>

						<div>
							<p><strong>{{ $product->teaser }}</strong></p>

							<div class="well well-sm">
								{!!  $product->description !!}
							</div>

							<h4>Auteurs</h4>
							<ul>
								@if(!$product->authors->isEmpty())
									@foreach($product->authors as $author)
										<li>{{ $author->name }}</li>
									@endforeach
								@endif
							</ul>

							<h4>Catégories</h4>
							<ul>
								@if(!$product->categories->isEmpty())
									@foreach($product->categories as $categorie)
										<li>{{ $categorie->title }}</li>
									@endforeach
								@endif
							</ul>

						</div>

						<hr/>

						<div class="cart-price">
							<form method="post" action="{{ url('cart/addProduct') }}" class="form-inline">{!! csrf_field() !!}
								<button type="submit" class="cart-btn2">Ajouter au panier</button>
								<span class="price">{{ $product->price_cents }} CHF</span>
								<input type="hidden" name="product_id" value="{{ $product->id }}">
							</form>
						</div>

					</div>

				</div>
			@endif

		</div>
	</section>

@stop