@extends('backend.layouts.master')
@section('content')

@if ($site)
    <!-- start row -->
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-3">
                            <h2><img height="30px" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></h2>
                            <h4>{{ $site->nom }}</h4>
                            <p>{{ $site->url }}</p>
                            <br/>
                            <p><a href="{{ url('admin/config') }}"><i class="fa fa-shopping-cart"></i> &nbsp;Configurations shop</a></p>
                            <p><a href="{{ url('admin/colloque') }}"><i class="fa fa-calendar"></i> &nbsp;Configurations colloques</a></p>
                            <p><a href="{{ url('admin/abo') }}"><i class="fa fa-bookmark"></i> &nbsp;Configurations abonnements</a></p>
                        </div>
                        <div class="col-md-9">

                            <div class="row">
                                <h2>&nbsp;</h2>
                                <div class="col-md-4">
                                    <a class="shortcut-tiles tiles-info" href="{{ url('admin/menus/'.$site->id) }}">
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-list"></i></div>
                                        </div>
                                        <div class="tiles-footer">Menus</div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="shortcut-tiles tiles-orange" href="{{ url('admin/pages/'.$site->id) }}">
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-file"></i></div>
                                        </div>
                                        <div class="tiles-footer">Pages</div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="shortcut-tiles tiles-success" href="{{ url('admin/arrets/'.$site->id) }}">
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-edit"></i></div>
                                        </div>
                                        <div class="tiles-footer">Arrêts</div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <a class="shortcut-tiles tiles-magenta" href="{{ url('admin/analyses/'.$site->id) }}">
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-dot-circle-o"></i></div>
                                        </div>
                                        <div class="tiles-footer">Analyses</div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="shortcut-tiles tiles-midnightblue" href="{{ url('admin/categories/'.$site->id) }}">
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-tasks"></i></div>
                                        </div>
                                        <div class="tiles-footer">Catégories</div>
                                    </a>
                                </div>
                        {{--        <div class="col-md-4">
                                    <a class="shortcut-tiles tiles-green" href="{{ url('admin/questions/'.$site->id) }}">
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-archive"></i></div>
                                        </div>
                                        <div class="tiles-footer">FAQ</div>
                                    </a>
                                </div>--}}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endif

@stop