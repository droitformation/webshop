@extends('layouts.bail.master')
@section('content')

	<div id="content" class="inner">
		<div class="row">
			<div class="col-md-12">
				<h3 class="line up">{{ $page->title }}</h3>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				{!! $page->content !!}
			</div>
		</div>

	 </div>
@stop
