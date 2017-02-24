@extends('team.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <p><a href="{{ url('team/colloque') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>
                                <?php $illustration = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                                <img style="height: 50px; float:left;margin-right: 15px; margin-bottom: 10px;" src="{{ secure_asset('files/colloques/illustration/'.$illustration.'') }}" />
                                <p>{{ $colloque->titre }}<br/><small>{{ $colloque->event_date }}</small></p>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <div class="table-responsive">
                        <h3>Inscriptions</h3>

                        <table class="table" style="margin-bottom: 0px; margin-top:20px;"><!-- Start inscriptions -->
                            <thead>
                            <tr>
                                <th class="col-sm-2">Déteteur</th>
                                <th class="col-sm-2">No</th>
                                <th class="col-sm-1">Prix</th>
                                <th class="col-sm-1">Date</th>
                                <th class="col-sm-2">Status</th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!$inscriptions_filter->isEmpty())
                                @foreach($inscriptions_filter as $inscription)
                                    @if($inscription->group_id)
                                        @include('team.colloques.rows.multiple', ['inscription' => $inscription])
                                    @else
                                        @include('team.colloques.rows.simple', ['inscription' => $inscription])
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

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop