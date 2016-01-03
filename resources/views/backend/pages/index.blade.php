@extends('backend.layouts.master')
@section('content')

<?php $helper = new \App\Droit\Helper\Helper(); ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/page/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Pages</div>
            <div class="panel-body">

                <p class="help-block"><i class="fa fa-crosshairs"></i> &nbsp;Cliquez-glissez les pages pour changer l'ordre dans le menu</p>
                <hr/>
                <div class="tab-container">
                    <ul class="nav nav-tabs">
                        @if(!$sites->isEmpty())
                            @foreach($sites as $site)
                                <li class="{{ $site->id == 1 ? 'active' : '' }}">
                                    <a data-toggle="tab" href="#site{{ $site->id }}"><img height="25px" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="tab-content">
                    <?php $grouped = $root->groupBy('site_id'); ?>
                    @if(!$sites->isEmpty())
                        @foreach($sites as $site)
                            <div id="site{{ $site->id }}" class="tab-pane {{ $site->id == 1 ? 'active' : '' }}">

                                @if(!$root->isEmpty())
                                    <div class="dd nestable_list" style="height: auto;">

                                        <ol class="dd-list sortable">
                                            @if(isset($grouped[$site->id]))
                                                @foreach($grouped[$site->id] as $page)
                                                    <?php echo $helper->renderNode($page); ?>
                                                @endforeach
                                            @endif
                                        </ol>

                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>

@stop