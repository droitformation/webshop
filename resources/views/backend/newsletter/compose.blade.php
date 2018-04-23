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
<?php
 /*            echo '<pre>';
                print_r($contents->first());
                echo '</pre>';exit(); */
                ?>

                @foreach($contents as $content)
                    @if($content->type_content == 'model')
                        <build-newsletter-models type="{{ $content->type_id }}"
                                                 site="{{ $content->site_id }}"
                                                 :content="{{ $content }}"
                                                 :campagne="{{ $campagne }}" _token="{{ csrf_token() }}"
                                                 url="{{ url('build/content') }}">
                        </build-newsletter-models>
                    @else
                        <build-newsletter type="{{ $content->type_id }}"
                                          site="{{ $content->site_id }}"
                                          :model="{{ $content }}"
                                          :campagne="{{ $campagne }}" _token="{{ csrf_token() }}"
                                          url="{{ url('build/content') }}">
                        </build-newsletter>
                    @endif
                @endforeach

                <build :blocs="{{ $blocs }}" :campagne="{{ $campagne }}" _token="{{ csrf_token() }}" url="{{ url('build/content') }}"></build>

            </div><!-- end 12 col -->
        </div><!-- end row -->
    </div>

@stop