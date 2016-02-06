@extends('backend.layouts.master')
@section('content')

@if ($site)

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h2><img height="30px" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></h2>
                    <h4>{{ $site->nom }}</h4>
                    <p>{{ $site->url }}</p>
                    <br/>
                    <p><a href="{{ url('admin/config') }}"><i class="fa fa-shopping-cart"></i> &nbsp;Configurations shop</a></p>
                    <p><a href="{{ url('admin/colloque') }}"><i class="fa fa-calendar"></i> &nbsp;Configurations colloques</a></p>
                    <p><a href="{{ url('admin/abo') }}"><i class="fa fa-bookmark"></i> &nbsp;Configurations abonnements</a></p>

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
        <div class="col-md-8">

            <div class="panel panel-info">
                <div class="panel-body">

                    <h4 class="text-info"><i class="fa fa-table"></i> &nbsp;Dernières inscriptions</h4>
                    <table class="table normalTable" id="" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Déteteur</th>
                            <th class="col-sm-2">No</th>
                            <th class="col-sm-2">Date</th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                        @if(!empty($inscriptions))
                            @foreach($inscriptions as $inscription)
                                <tr {!! ($inscription->group_id > 0 ? 'class="isGoupe"' : '') !!}>
                                    <td><a class="btn btn-info btn-sm" href="{{ url('admin/inscription/'.$inscription->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td>
                                        <?php
                                        echo ($inscription->group_id > 0 ? '<span class="label label-default">Groupe '.$inscription->group_id.'</span>' : '');
                                        if($inscription->inscrit)
                                        {
                                            echo ($inscription->inscrit->company != '' ? '<p><strong>'.$inscription->adresse_facturation->company.'</strong></p>' : '');
                                            echo '<p>'.($inscription->group_id > 0 ? $inscription->participant->name : $inscription->inscrit->name).'</p>';
                                        }
                                        ?>
                                    </td>
                                    <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                    <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-success">
                <div class="panel-body">
                    <h4 class="text-success"><i class="fa fa-shopping-cart"></i> &nbsp;Dernières commandes</h4>
                    <table class="table normalTable" id="" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Déteteur</th>
                            <th class="col-sm-3">No</th>
                            <th class="col-sm-2">Date</th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                        @if(!$orders->isEmpty())
                            @foreach($orders as $order)
                                <tr>
                                    <td><a class="btn btn-success btn-sm" href="{{ url('admin/order/'.$order->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td>{{ $order->order_adresse->name }}</td>
                                    <td><strong>{{ $order->order_no }}</strong></td>
                                    <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>


@endif
@stop