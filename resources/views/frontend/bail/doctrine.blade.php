@extends('frontend.bail.layouts.master')
@section('content')

	<div id="content" class="inner">
		<div class="row">
			<div class="col-md-12">
				<h3 class="line up">{{ $page->title }}</h3>
				{!! $page->content !!}
			</div>
		</div>
		@if(!$doctrines->isEmpty() && !$order->isEmpty())

			<div class="row">
				<div class="col-md-12">
					<table class="table" id="doctrine">
						<tr>
							<th width="10%">Catégorie</th>
							<th width="20%">Édition</th>
							<th width="10%">Année</th>
							<th width="35%">Description</th>
							<th width="15%">Auteur</th>
							<th width="10%">Lien</th>
						</tr>
						@foreach($order as $categorie)
							<?php $group = $doctrines->pull($categorie); ?>
							@foreach($group as $row)
								<tr>
									<td><i>{{ ucfirst($categorie) }}</i></td>
									<td>{{ $row->seminaire ? $row->seminaire->title : '' }}</td>
									<td>{{ $row->seminaire ? $row->seminaire->year : '' }}</td>
									<td>{{ $row->title }}</td>
									<td>{{ $row->authors->implode('name', ', ') }}</td>
									<td>
										<a href="{{ asset('product') }}">Aquérir</a>
									</td>
								</tr>
							@endforeach
						@endforeach
					</table>
				</div>
			</div>

		@endif
	</div>
@stop
