@extends('frontend.matrimonial.layouts.app')
@section('content')

    <div id="content" class="inner">
		<div id="appComponent" style="min-height: 300px;">
			<jurisprudence site="3" :categories="{{ $categories }}"></jurisprudence>
		</div>
    </div>
		
@stop
