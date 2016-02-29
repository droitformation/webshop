@extends('backend.layouts.master')
@section('content')
    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="{{ url('admin/inscription/colloque/'.$colloque->id) }}" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                    <a href="{{ url('admin/inscription/rappel/make/'.$colloque->id) }}" class="btn btn-warning pull-right"><i class="fa fa-bell"></i> &nbsp;Générer tous les rappels</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h3>Rappels inscriptions simples</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-1"></th>
                                <th class="col-sm-2">Déteteur</th>
                                <th class="col-sm-1">No</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-md-3">Rappels</th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                                @if(!$inscriptions->isEmpty())
                                    @foreach($inscriptions as $inscription)
                                        @include('backend.rappels.partials.simple', ['inscription' => $inscription])
                                    @endforeach
                                @endif

                            </tbody>
                        </table><!-- End inscriptions -->

                        {!! $inscriptions->links() !!}

                    </div>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h3>Rappels inscriptions multiples</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-1"></th>
                                <th class="col-sm-2">Déteteur</th>
                                <th class="col-sm-2">Participant et N°</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-md-3">Rappels</th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!$groupes->isEmpty())
                                @foreach($groupes as $group)
                                    @include('backend.rappels.partials.multiple', ['group' => $group])
                                @endforeach
                            @endif

                            </tbody>
                        </table><!-- End inscriptions -->

                        {!! $groupes->links() !!}

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop