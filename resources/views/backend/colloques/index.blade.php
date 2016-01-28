@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/colloque/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <h3>Colloques</h3>

        <?php

            $actives = $colloques->filter(function ($colloque) {
                return $colloque->end_at > date('Y-m-d');
            });

            $archives = $colloques->filter(function ($colloque) {
                return $colloque->end_at <= date('Y-m-d');
            });
        ?>

        <div class="panel panel-midnightblue">
            <div class="panel-heading"><h4><i class="fa fa-tasks"></i> &nbsp;En cours</h4></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table simple-table" style="margin-bottom: 0px;">
                        <thead>
                        <tr>
                            <th class="col-sm-2">Action</th>
                            <th class="col-sm-4">Titre</th>
                            <th class="col-sm-2">Sujet</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                            @if(!$actives->isEmpty())
                                @foreach($actives as $colloque)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a class="btn btn-sky btn-sm" href="{{ url('admin/colloque/'.$colloque->id) }}">&Eacute;diter</a>
                                                <a class="btn btn-success btn-sm" href="{{ url('admin/inscription/colloque/'.$colloque->id) }}">Inscriptions</a>
                                            </div>
                                        </td>
                                        <td>
                                            @if($colloque->illustration)
                                                <img style="height: 50px; float:left; margin-right: 10px;" src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" />
                                            @else
                                                <img style="height: 50px; float:left; margin-right: 10px;" src="{{ asset('files/colloques/illustration/illu.png') }}" />
                                            @endif
                                            <strong>{{ $colloque->titre }}</strong>
                                            <p>{{ $colloque->event_date }}</p>
                                        </td>
                                        <td><strong>{{ $colloque->sujet }}</strong></td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/colloque/'.$colloque->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-action="{{ $colloque->titre }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>


            </div>
        </div>

        <div class="panel panel-orange">
            <div class="panel-heading"><h4><i class="fa fa-tasks"></i> &nbsp;Archives</h4></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table simple-table" style="margin-bottom: 0px;">
                        <thead>
                        <tr>
                            <th class="col-sm-2">Action</th>
                            <th class="col-sm-4">Titre</th>
                            <th class="col-sm-2">Sujet</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                        @if(!$archives->isEmpty())
                            @foreach($archives as $colloque)
                                <tr>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a class="btn btn-sky btn-sm" href="{{ url('admin/colloque/'.$colloque->id) }}">&Eacute;diter</a>
                                            <a class="btn btn-success btn-sm" href="{{ url('admin/inscription/colloque/'.$colloque->id) }}">Inscriptions</a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($colloque->illustration)
                                            <img style="height: 50px; float:left; margin-right: 10px;" src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" />
                                        @else
                                            <img style="height: 50px; float:left; margin-right: 10px;" src="{{ asset('files/colloques/illustration/illu.png') }}" />
                                        @endif
                                        <strong>{{ $colloque->titre }}</strong>
                                        <p>{{ $colloque->event_date }}</p>
                                    </td>
                                    <td><strong>{{ $colloque->sujet }}</strong></td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/colloque/'.$colloque->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-action="{{ $colloque->titre }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>


            </div>
        </div>


    </div>
</div>

@stop