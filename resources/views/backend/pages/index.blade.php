@extends('backend.layouts.master')
@section('content')

<?php $helper = new \App\Droit\Helper\Helper(); ?>


<div class="row">
    <div class="col-md-push-8 col-md-4 col-xs-12">
        <form action="{{ url('admin/page/create') }}" method="get">
            <div class="form-group input-group">

                <select class="form-control" name="template">
                    @if(!empty($templates))
                        @foreach($templates as $template => $nom)
                            <option {{ $template == 'page' ? 'selected' : '' }} value="{{ $template }}">{{ $nom }}</option>
                        @endforeach
                    @endif
                </select>

                <span class="input-group-btn">
                    <button class="btn btn-success" type="submit"><i class="fa fa-plus"></i> &nbsp;Ajouter</button>
                </span>
            </div><!-- /input-group -->
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

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