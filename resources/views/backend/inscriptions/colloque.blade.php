@extends('backend.layouts.master')
@section('content')
    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <div class="row">
        <div class="col-md-12">

            <p><a href="{{ url('admin/colloque') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h4>
                                @if($colloque->illustration)
                                    <img style="height: 50px; float:left; margin-right: 15px;margin-bottom: 10px;" src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" />
                                @else
                                    <img style="height: 50px; float:left;margin-right: 15px; margin-bottom: 10px;" src="{{ asset('files/colloques/illustration/illu.png') }}" />
                                @endif
                                <p><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a><br/><small>{{ $colloque->event_date }}</small></p>
                            </h4>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href="{{ url('admin/inscription/create/'.$colloque->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une inscription</a>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ url('admin/export/inscription') }}" method="POST" class="form-horizontal">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="id" value="{{ $colloque->id }}">
                                {!! csrf_field() !!}

                                <div class="row">
                                    <div class="col-md-7">
                                        <h4>Infos</h4>
                                        @if(!empty($names))
                                            <?php $i = 1; ?>
                                            @foreach($names as $key => $name)
                                                <div class="checkbox-inline checkbox-border">
                                                    <label><input class="checkbox_all" value="{{ $name }}" name="columns[{{ $key }}]" type="checkbox"> {{ $name }}</label>
                                                </div>
                                                <?php $i++; ?>
                                                {!! $i == 7 ? '<div class="clearfix"></div>' : '' !!}
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <h4>Tri</h4>
                                        <div class="radio">
                                            <label><input type="radio" name="sort" value="" checked> Normal</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="sort" value="1"> Par options</label>
                                        </div>
                                    </div>
                                </div>
                                <p style="margin-top: 10px;" class="pull-left"><input type="checkbox" id="select_all" /> &nbsp;<span class="text-primary">Séléctionner tous</span></p>
                                <button type="submit" class="btn btn-inverse pull-right"><i class="fa fa-download"></i> &nbsp;Exporter la liste</button>
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

                            @if(!empty($inscriptions))
                                @foreach($inscriptions as $inscription)
                                    @include('backend.inscriptions.partials.row', ['inscription' => $inscription])
                                @endforeach
                            @endif

                            </tbody>
                        </table><!-- End inscriptions -->

                        {!! $inscriptions->links() !!}

                        <p><br/><a class="btn btn-warning btn-sm pull-right" data-toggle="collapse" href="#desinscriptionTable">Désinscriptions</a></p>
                    </div>
                </div>
            </div>

            <div class="collapse" id="desinscriptionTable">

                <div class="panel panel-warning">
                    <div class="panel-body">
                        <table class="table" id="generic" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-2">Nom</th>
                                <th class="col-sm-2">Email</th>
                                <th class="col-sm-2">Participant</th>
                                <th class="col-sm-2">No</th>
                                <th class="col-sm-2">Date</th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!empty($desinscriptions))
                                @foreach($desinscriptions as $inscription)

                                    <?php $style = ($inscription->group_id > 0 ? 'class="isGoupe"' : ''); setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>
                                    <tr {!! $style !!}>
                                        <td>
                                            <form action="{{ url('admin/inscription/restore/'.$inscription->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="POST">
                                                {!! csrf_field() !!}
                                                <button data-what="Restaurer" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-warning btn-xs deleteAction">Restaurer</button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php
                                            echo ($inscription->group_id > 0 ? '<span class="label label-default">Groupe '.$inscription->group_id.'</span>' : '');
                                            echo ($inscription->adresse_facturation->company != '' ? '<p><strong>'.$inscription->adresse_facturation->company.'</strong><br/></p>' : '');
                                            echo '<p>'.$inscription->adresse_facturation->civilite_title.' '.$inscription->adresse_facturation->name.'</p>';
                                            ?>
                                        </td>
                                        <td>{{ $inscription->adresse_facturation->email }}</td>
                                        <td><?php echo ($inscription->group_id > 0 ? $inscription->participant->name :''); ?>
                                        </td>
                                        <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                        <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
                                    </tr>

                                @endforeach
                            @endif

                            </tbody>
                        </table><!-- End desinscriptions -->

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop