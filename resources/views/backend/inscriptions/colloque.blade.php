@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <p>
                <a href="{{ url('admin/colloque') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                <a class="btn btn-brown pull-right" href="{{ url('admin/inscription/rappels/'.$colloque->id) }}"><i class="fa fa-exclamation-triangle"></i> &nbsp; Tous les rappels</a>
            </p>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h4>
                                <?php $illustration = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                                <img style="height: 50px; float:left;margin-right: 15px; margin-bottom: 10px;" src="{{ asset('files/colloques/illustration/'.$illustration.'') }}" />
                                <p><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a><br/><small>{{ $colloque->event_date }}</small></p>
                            </h4>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href="{{ url('admin/inscription/create/'.$colloque->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une inscription</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-10">
                            @include('backend.inscriptions.filters.infos')
                        </div>
                        <div class="col-md-2">
                            @include('backend.inscriptions.filters.badge')
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <div class="table-responsive">
                        <h3>Inscriptions</h3>

                       <form class="form-horizontal pull-right" action="{{ url('admin/inscription/colloque/'.$colloque->id) }}" method="post">{!! csrf_field() !!}
                            <div class="input-group">
                                <input type="text" class="form-control" name="inscription_no" placeholder="Recherche par numéro...">
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>

                        <table class="table" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                            <thead>
                            <tr>
                                <th class="col-sm-1 no-sort">Action</th>
                                <th class="col-sm-2">Déteteur</th>
                                <th class="col-sm-2">No</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-1 no-sort"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!$inscriptions->isEmpty())
                                @foreach($inscriptions as $inscription)
                                    @include('backend.inscriptions.partials.row', ['inscription' => $inscription])
                                @endforeach
                            @else
                                <tr><td colspan="7"><p>Aucune inscriptions pour le moment</p></td></tr>
                            @endif

                            </tbody>
                        </table><!-- End inscriptions -->



                        <hr/>
                        <p><br/><a class="btn btn-warning btn-sm pull-right" href="{{ url('admin/inscription/desinscription/'.$colloque->id) }}">Désinscriptions</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop