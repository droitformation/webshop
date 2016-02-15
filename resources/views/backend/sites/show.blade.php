@extends('backend.layouts.master')
@section('content')

@if($site)

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h3><strong>{{ $site->nom }}</strong></h3>
                    <p>{{ $site->url }}</p>

                    @if($site->id == 1)
                        <p><a href="{{ url('admin/config/shop') }}"><i class="fa fa-shopping-cart"></i> &nbsp;Configurations shop</a></p>
                        <p><a href="{{ url('admin/config/colloque') }}"><i class="fa fa-calendar"></i> &nbsp;Configurations colloques</a></p>
                        <p><a href="{{ url('admin/config/abo') }}"><i class="fa fa-bookmark"></i> &nbsp;Configurations abonnements</a></p>
                    @endif

                    <hr/>
                    <a class="shortcut-tiles tiles-sky" href="{{ url('admin/menus/'.$site->id) }}">
                        <div class="tiles-body">
                            <div class="pull-left"><i class="fa fa-list"></i></div>
                        </div>
                        <div class="tiles-footer">Menus</div>
                    </a>
                    <a class="shortcut-tiles tiles-orange" href="{{ url('admin/pages/'.$site->id) }}">
                        <div class="tiles-body">
                            <div class="pull-left"><i class="fa fa-file"></i></div>
                        </div>
                        <div class="tiles-footer">Pages</div>
                    </a>
                    <a class="shortcut-tiles tiles-success" href="{{ url('admin/arrets/'.$site->id) }}">
                        <div class="tiles-body">
                            <div class="pull-left"><i class="fa fa-edit"></i></div>
                        </div>
                        <div class="tiles-footer">Arrêts</div>
                    </a>
                    <a class="shortcut-tiles tiles-magenta" href="{{ url('admin/analyses/'.$site->id) }}">
                        <div class="tiles-body">
                            <div class="pull-left"><i class="fa fa-dot-circle-o"></i></div>
                        </div>
                        <div class="tiles-footer">Analyses</div>
                    </a>
                    <a class="shortcut-tiles tiles-midnightblue" href="{{ url('admin/categories/'.$site->id) }}">
                        <div class="tiles-body">
                            <div class="pull-left"><i class="fa fa-tasks"></i></div>
                        </div>
                        <div class="tiles-footer">Catégories</div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">

            @if($site->id == 1)
                @include('backend.sites.partials.orders')
                @include('backend.sites.partials.inscriptions')
            @endif

            @if(!$site->arrets->isEmpty())
                @include('backend.sites.partials.arrets')
            @endif

            @if(!$site->analyses->isEmpty())
                @include('backend.sites.partials.analyses')
            @endif


        </div>
    </div>


@endif
@stop