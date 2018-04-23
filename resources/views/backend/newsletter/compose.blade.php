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

    <div id="StyleNewsletter" class="onBuild">
        <!-- Logos -->
        @include('emails.newsletter.send.logos')
        <!-- Header -->
        @include('emails.newsletter.send.header')

        @if(config('newsletter.pdf'))
            @include('emails.newsletter.send.link')
        @endif
    </div>

    <div id="appComponent">
        <div class="row">
            <div class="col-md-12">

                <edit-build :contents="{{ $contents }}" :campagne="{{ $campagne }}" _token="{{ csrf_token() }}" url="{{ url('build/content') }}" site="{{ $campagne->newsletter->site_id }}"></edit-build>
                <build :blocs="{{ $blocs }}" :campagne="{{ $campagne }}" _token="{{ csrf_token() }}" url="{{ url('build/content') }}"></build>

            </div><!-- end 12 col -->
        </div><!-- end row -->
    </div>

@stop