@extends('frontend.bail.layouts.master')
@section('content')

	<div id="content" class="inner inner-app">
		<div id="appComponent" style="min-height: 450px;">
			<jurisprudence slug="bail" site="2" :categories="{{ $categories->where('hideOnSite',0) }}" :years="{{ $years }}"></jurisprudence>
		</div>
	</div>

@stop
