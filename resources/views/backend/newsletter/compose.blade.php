@extends('backend.layouts.master')
@section('content')

    <style type="text/css">
        #StyleNewsletter h2, #StyleNewsletterCreate h2
        #StyleNewsletter .contentForm h3, #StyleNewsletter .contentForm h4,
        #StyleNewsletterCreate .contentForm h3, #StyleNewsletterCreate .contentForm h4
        {
            color: {{ $campagne->newsletter->color }};
        }
    </style>

    <div id="appComponent">
        <div class="row">
            <div class="col-md-12">

                <build-newsletter type="1" :campagne="{{ $campagne }}" _token="{{ csrf_token() }}" url="{{ url('build/content') }}"></build-newsletter>

                <build-newsletter-models type="5" site="2" :campagne="{{ $campagne }}" _token="{{ csrf_token() }}" url="{{ url('build/content') }}"></build-newsletter-models>

            </div><!-- end 12 col -->
        </div><!-- end row -->
    </div>

@stop