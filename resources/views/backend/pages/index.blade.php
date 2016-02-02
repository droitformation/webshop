@extends('backend.layouts.master')
@section('content')

<?php $helper = new \App\Droit\Helper\Helper(); ?>

<div class="row">
    <div class="col-md-6">
        <p class="help-block"><i class="fa fa-crosshairs"></i> &nbsp;Cliquez-glissez les pages pour changer l'ordre dans le menu</p>
    </div>
    <div class="col-md-6 text-right">
        <p><a href="{{ url('admin/page/create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></p>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-12">

        <?php $grouped = $root->groupBy('site_id'); ?>
        @if(!$sites->isEmpty())
            @foreach($sites as $site)

                <p><img height="40" src="{{ asset('logos/'.$site->logo) }}" alt="logo"></p>

                <div class="panel panel-primary">
                    <div class="panel-body">

                        @if(isset($grouped[$site->id]))
                            <?php $menus_names = $grouped[$site->id]->groupBy('menu_id'); ?>
                            <div class="row">
                                @foreach($menus_names as $menu_id => $menu)
                                    <div class="col-md-6">
                                        <h4>{{ $menus[$menu_id] }}</h4>
                                        <div class="dd nestable_list" style="height: auto;">
                                            <ol class="dd-list sortable">
                                                @if(!$menu->isEmpty())
                                                    @foreach($menu as $page)
                                                        <?php echo $helper->renderNode($page); ?>
                                                    @endforeach
                                                @endif
                                            </ol>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

            @endforeach
        @endif

    </div>
</div>

@stop