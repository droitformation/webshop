@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/faq/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="tab-container tab-left">
            <ul class="nav nav-tabs">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <li class="{{ $site->id == 1 ? 'active' : '' }}">
                            <a data-toggle="tab" href="#site{{ $site->id }}">
                                <img height="25px" src="{{ secure_asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" />
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="tab-content">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <?php $site_pages = $site->pages->pluck('id')->toArray(); ?>
                        <div id="site{{ $site->id }}" class="tab-pane {{ $site->id == 1 ? 'active' : '' }}">

                            @if(!$content->isEmpty())
                                <?php $pages = $content->groupBy('page_id'); ?>
                                @foreach($pages as $page_id => $page)
                                    @if(in_array($page_id,$site_pages))
                                        <h4>{{ $page->first()->page->title }}</h4>
                                        @include('backend.content.partials.type',['content' => $page])
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>

@stop