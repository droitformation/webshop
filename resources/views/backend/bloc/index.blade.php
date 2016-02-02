@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/bloc/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="tab-container tab-left">
            <ul class="nav nav-tabs">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <li class="{{ $site->id == 1 ? 'active' : '' }}">
                            <a data-toggle="tab" href="#site{{ $site->id }}">
                                <img height="25px" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" />
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="tab-content">
                @if(!$sites->isEmpty())

                    @foreach($sites as $site)
                        <?php $site_pages[$site->id] = $site->pages->pluck('id')->toArray(); ?>
                    @endforeach

                    @foreach($sites as $site)
                        <div id="site{{ $site->id }}" class="tab-pane {{ $site->id == 1 ? 'active' : '' }}" style="min-height: 150px">

                            <?php
                                $pages    = $site_pages[$site->id];
                                $filtered = $blocs->filter(function ($bloc, $key) use ($pages)
                                {
                                    $has   = $bloc->pages->pluck('id')->toArray();
                                    $exist = array_intersect($pages,$has);

                                    return count($exist) > 0 ? $bloc : false;
                                });
                            ?>

                            @if(!$blocs->isEmpty())
                                @include('backend.bloc.partials.type',['bloc' => $filtered])
                            @endif

                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>

@stop