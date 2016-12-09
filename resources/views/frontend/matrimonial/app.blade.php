@extends('frontend.matrimonial.layouts.app')
@section('content')

    <div id="content" class="inner inner-app">

		<h2>Jurisprudence</h2>

		<div id="appComponent" style="min-height: 450px;">
			<jurisprudence slug="matrimonial" site="3" :categories="{{ $categories }}" :years="{{ $years }}"></jurisprudence>
		</div>
    </div>
		
@stop
