@extends('backend.layouts.master')
@section('content')

<?php $helper = new \App\Droit\Helper\Helper(); ?>

<div class="row">
    <div class="col-md-6">
        <p class="help-block"><i class="fa fa-crosshairs"></i> &nbsp;Cliquez-glissez les pages pour changer l'ordre dans le menu</p><hr/>
    </div>
    <div class="col-md-6 text-right">
        <p><a href="{{ url('admin/page/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        <?php $grouped = $root->groupBy('site_id'); ?>
        @if(!$sites->isEmpty())
            <div class="row">
            @foreach($sites as $site)
                <div class="col-md-4 col-xs-12">
                    <h4>{{ $site->nom }}</h4>
                    <div class="panel panel-primary">
                        <div class="panel-body">
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
                    </div>
                </div>

            @endforeach
            </div>
        @endif

    </div>
</div>

@stop