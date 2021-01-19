
@extends('backend.layouts.master')
@section('content')

    <div class="row" id="appComponent">
        <div class="col-md-12">

            <p>
                <?php $back = session()->has('colloque_archive') ? url('admin/colloque/archive/'.session()->get('colloque_archive')) : url('admin/colloque'); ?>
                <a href="{{ $back }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                <a class="btn btn-brown pull-right" href="{{ url('admin/inscription/rappels/'.$colloque->id) }}"><i class="fa fa-exclamation-triangle"></i> &nbsp; Tous les rappels</a>
            </p>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h4>
                                <?php $illustration = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                                <img style="height: 50px; float:left;margin-right: 15px; margin-bottom: 10px;" src="{{ secure_asset('files/colloques/illustration/'.$illustration.'') }}" />
                                <p><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a><br/><small>{{ $colloque->event_date }}</small></p>
                            </h4>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href="{{ url('admin/inscription/create/'.$colloque->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une inscription</a>
                        </div>
                    </div>
                </div>
            </div>

            @if(!$inscriptions->isEmpty())
                <!-- export filters panel -->
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div id="excelGroup">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group btn-group-export">
                                        <button class="btn btn-inverse btn-sm" type="button" data-toggle="collapse" data-target="#exportExcel" aria-expanded="false" aria-controls="exportExcel">
                                            <i class="fa fa-download"></i> &nbsp;Exporter liste excel
                                        </button>
                                        <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#exportBadge" aria-expanded="false" aria-controls="exportBadge">
                                            <i class="fa fa-files-o"></i> &nbsp;Exporter Badges
                                        </button>
                                        <a href="{{ url('admin/export/qrcodes/'.$colloque->id) }}" target="_blank" class="btn btn-sm btn-brown">
                                            <i class="fa fa-qrcode"></i> &nbsp;Exporter qrcodes
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="collapse" id="exportExcel">
                                        @include('backend.inscriptions.filters.excel')
                                    </div>
                                    <div class="collapse" id="exportBadge">
                                        @include('backend.inscriptions.filters.badge')
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end export filters -->
            @endif

            @if(!$invalid->isEmpty())
                @include('backend.inscriptions.partials.invalid')
            @endif

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <div class="table-responsive">
                        <h3>Inscriptions</h3>

                        @include('backend.inscriptions.partials.filters')

                        <table class="table" style="margin-bottom: 0px; margin-top:20px;"><!-- Start inscriptions -->
                            <thead>
                            <tr>
                                <th class="col-sm-1 no-sort">Action</th>
                                <th class="col-sm-2">Détenteur</th>
                                <th class="col-sm-2">No</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-1 no-sort"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!$inscriptions_filter->isEmpty())
                                @foreach($inscriptions_filter as $inscription)
                                    @if($inscription->group_id)
                                        @include('backend.inscriptions.rows.multiple', ['inscription' => $inscription])
                                    @else
                                        @include('backend.inscriptions.rows.simple', ['inscription' => $inscription])
                                    @endif
                                @endforeach
                            @else
                                <tr><td colspan="7"><p>Aucune inscriptions pour le moment</p></td></tr>
                            @endif

                            </tbody>
                        </table><!-- End inscriptions -->

                        @if($inscriptions instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{$inscriptions->links()}}
                        @endif

                        <hr/>
                        <p><br/><a class="btn btn-warning btn-sm pull-right" href="{{ url('admin/desinscription/'.$colloque->id) }}">Désinscriptions</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop