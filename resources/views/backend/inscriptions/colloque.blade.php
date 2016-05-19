@extends('backend.layouts.master')
@section('content')

    <?php $civilites = $civilites->pluck('title','id')->all(); ?>

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
                    <hr/>

                    <div class="row">
                        <div class="col-md-10">
                            <form action="{{ url('admin/export/inscription') }}" method="POST" class="form-horizontal">
                                <div class="row">
                                    <input type="hidden" name="_method" value="POST">
                                    <input type="hidden" name="id" value="{{ $colloque->id }}">{!! csrf_field() !!}
                                    <div class="col-md-7">
                                        <h4>Infos</h4>
                                        <p><input type="checkbox" id="select_all" /> &nbsp;<span class="text-primary">Séléctionner tous</span></p>
                                        @if(!empty($names))
                                            @foreach($names as $key => $name)
                                                <div class="checkbox-inline checkbox-border">
                                                    <label><input class="checkbox_all" value="{{ $name }}" name="columns[{{ $key }}]" type="checkbox"> {{ $name }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-5" style="border-right: 1px solid #e6e7e8; padding-right: 15px;">
                                        <h4 style="margin-bottom: 0;">Tri</h4>
                                        <div class="radio"><label><input type="radio" name="sort" value="" checked> Normal</label></div>
                                        <div class="radio"><label><input type="radio" name="sort" value="1"> Par options</label></div>
                                        <div class="text-right" style="margin-top: 15px;">
                                            <button type="submit" class="btn btn-inverse" style="margin-top: 5px;"><i class="fa fa-download"></i> &nbsp;Exporter liste excel</button>
                                            <a href="{{ url('admin/export/qrcodes/'.$colloque->id) }}" class="btn btn-brown" style="margin-top: 5px;">
                                                <i class="fa fa-qrcode"></i> &nbsp;Exporter qrcodes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ url('admin/export/badges') }}" method="POST" class="form-horizontal">
                                <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">{!! csrf_field() !!}
                                <h4>Badges</h4>
                                <select class="form-control" name="format">
                                    @if($badges)
                                        <optgroup label="Etiquettes">
                                            @foreach($badges as $code => $config)
                                                <option value="pdf|{{$code}}">{{ $config['etiquettes'] }} badges par page</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                <br/>
                                <button type="submit" class="btn btn-primary pull-right" style="margin-top: 5px;"><i class="fa fa-files-o"></i> &nbsp;Générer</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <div class="table-responsive">
                        <h3>Inscriptions</h3>
                        <table class="table" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-2">Déteteur</th>
                                <th class="col-sm-2">No</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-1"></th>
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

                        {!! $inscriptions->links() !!}

                        <hr/>
                        <p><br/><a class="btn btn-warning btn-sm pull-right" href="{{ url('admin/inscription/desinscription/'.$colloque->id) }}">Désinscriptions</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop