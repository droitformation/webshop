@extends('frontend.matrimonial.layouts.master')
@section('content')

		<!-- Illustration -->

<div id="content" class="inner">

	<div class="row">
		<div class="col-md-12">
			<h3 class="line up">Newsletter</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">

			<p><a href="{{ url('matrimonial/page/newsletter/'.$campagne->newsletter_id ) }}"><i class="fa fa-arrow-circle-left"></i> Retour</a></p>
			<h2>{{ $campagne->sujet }}</h2>
			<h3>{{ $campagne->auteurs }}</h3>

			<hr/>

			@if(!empty($content))
				@foreach($content as $bloc)
					<div class="content-bloc">
						{!! view('frontend/newsletter/content/'.$bloc->type->partial)->with(
							 [
								'bloc' => $bloc ,
								'site' => $site ,
								'categories'    => $categories,
								'imgcategories' => $imgcategories
							]
						)->__toString()  !!}
					</div>
				@endforeach
			@endif

		</div>
	</div>

</div>
@stop
