@extends('backend.layouts.master')
@section('content')

    <style type="text/css">
        #StyleNewsletter h2, #StyleNewsletterCreate h2
        #StyleNewsletter .contentForm h3, #StyleNewsletter .contentForm h4,
        #StyleNewsletterCreate .contentForm h3, #StyleNewsletterCreate .contentForm h4
        {
            color: {{ $campagne->newsletter->color }};
        }
        #StyleNewsletter .resetTable.alert-dumois{
            border-top: 1px solid {{ $campagne->newsletter->color }};
            border-left:1px solid {{ $campagne->newsletter->color }};
            border-right:1px solid {{ $campagne->newsletter->color }};
        }
    </style>

    <div class="component-build"><!-- Start component-build -->
        <div id="optionsNewsletter">

            <a href="{{ url('build/campagne/'.$campagne->id.'/edit') }}" class="btn btn-primary btn-block"><i class="fa fa-pencil"></i>  &nbsp;&Eacute;diter la campagne</a>
            <a target="_blank" href="{{ url('campagne/'.$campagne->id) }}" class="btn btn-info btn-block"><i class="fa fa-eye"></i>  &nbsp;Aperçu de la campagne</a>
            <a href="{{ url('build/content/'.$campagne->id) }}" class="btn btn-warning btn-block"><i class="fa fa-sort"></i>  &nbsp;Ordre</a>

            @if(!$campagne->send_at)
                <hr/>

                <form action="{{ url('build/send/test') }}" enctype="multipart/form-data" method="POST" class="form">{!! csrf_field() !!}
                    <label><strong>Envoyer un test</strong></label>
                    <div class="input-group">
                        <input required name="email" value="" type="email" class="form-control">
                        <input name="id" value="{{ $campagne->id }}" type="hidden">
                        <span class="input-group-btn"><button class="btn btn-brown" type="submit">Go!</button></span>
                    </div><!-- /input-group -->
                </form>
            @endif
        </div>


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
                <div id="StyleNewsletter" style="margin: 0; width: auto;">
                    @if(!$campagne->content->isEmpty())
                        <div class="row">
                            <div class="col-md-7" id="StyleNewsletterCreate" style="position: relative;">
                                @foreach($campagne->content as $bloc)

                                    <div style="position: relative;">
                                        <edit-bloc
                                                :bloc="{{ $bloc }}"
                                                :campagne="{{ $campagne }}"
                                                :type="{{ $bloc->type_id }}"
                                                :newsletter="{{ $campagne->newsletter }}"
                                                _token="{{ csrf_token() }}"
                                                url="{{ url('build/content') }}"
                                                site="{{ $campagne->newsletter->site_id }}">
                                        </edit-bloc>

                                        <div id="bloc_{{ $bloc->id }}">
                                            @include('emails.newsletter.send.'.$bloc->type->partial, ['bloc' => $bloc, 'arret' => (isset($bloc->arret) ? $bloc->arret : null) ,'campagne' => $campagne])
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                    @endif


                    <!-- Building blocs newsletter -->
                    <build :blocs="{{ $blocs }}"
                           :campagne="{{ $campagne }}"
                           :newsletter="{{ $campagne->newsletter }}"
                           _token="{{ csrf_token() }}"
                           url="{{ url('build/content') }}"
                           site="{{ $campagne->newsletter->site_id }}"
                    >
                    </build>
                </div>
            </div><!-- end 12 col -->
        </div><!-- end row -->
    </div>
</div>

@stop