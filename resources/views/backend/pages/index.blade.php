@extends('backend.layouts.master')
@section('content')

<?php $helper = new \App\Droit\Helper\Helper(); ?>
<?php $site   = $sites->find($current_site); ?>

<div class="row">
    <div class="col-md-6">
        <h4>Pages</h4>
        <p class="help-block"><i class="fa fa-crosshairs"></i> &nbsp;Cliquez-glissez les pages pour changer l'ordre dans le menu</p>
    </div>
    <div class="col-md-6 text-right">
        <p><a id="addPage" href="{{ url('admin/page/create/'.$current_site) }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></p>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary">
            <div class="panel-body">
                @if(!$pages->isEmpty())
                <?php $menus_names = $pages->groupBy('menu_id'); ?>
                    <div class="row">
                        @foreach($menus_names as $menu_id => $menu)
                            <div class="col-md-6">
                                <h4><a class="btn btn-info btn-sm" href="{{ url('admin/menu/'.$menu_id) }}"><i class="fa fa-edit"></i></a> &nbsp;{{ $menus[$menu_id] }}</h4>
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
                @else
                    <p>Encore aucune page</p>
                @endif
            </div>
        </div>

    </div>
</div>

@stop