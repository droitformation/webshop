@extends('backend.layouts.master')
@section('content')
    <?php $helper = new \App\Droit\Helper\Helper(); ?>
    <div class="row">
        <div class="col-md-12">

            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-1">
                            <a href="#"><img style="height: 80px;" src="{{ asset($colloque->illustration) }}"></a>
                        </div>
                        <div class="col-md-9">
                            <h4><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a></h4>
                            <p>{{ $colloque->event_date }}</p>
                        </div>
                        <div class="col-md-2">
                            <div class="pull-right">
                                <a href="{{ url('admin/inscription/create/'.$colloque->id) }}" class="btn btn-success btn-block">
                                    <i class="fa fa-plus"></i> &nbsp;Ajouter une inscription</a>
                                <a class="btn btn-warning btn-block" data-toggle="collapse" href="#desinscriptionTable" aria-expanded="false" aria-controls="desinscriptionTable">Désinscriptions</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-tasks"></i> &nbsp;Inscriptions</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">

                        <table class="table generic" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                            <thead>
                            <tr>
                                <th class="col-sm-1">Groupe</th>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-2">Déteteur</th>
                                <th class="col-sm-2">Email</th>
                                <th class="col-sm-2">Participant</th>
                                <th class="col-sm-1">No</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-sm-1"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!empty($inscriptions))
                                @foreach($inscriptions as $inscription)

                                    @if(is_array($inscription))
                                        @foreach($inscription as $register)
                                            @include('backend.inscriptions.partials.row', ['inscription' => $register, 'group' => true])
                                        @endforeach
                                    @else
                                        @include('backend.inscriptions.partials.row', ['inscription' => $inscription, 'group' => false])
                                    @endif

                                @endforeach
                            @endif

                            </tbody>
                        </table><!-- End inscriptions -->
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