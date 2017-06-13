@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-info">
                <div class="panel-body">

                    @if(!$inscriptions->isEmpty())

                        <h4><i class="fa fa-users"></i> &nbsp;Commandes 2016</h4>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="col-sm-3">Nom</th>
                                <th class="col-sm-3">Participant</th>
                                <th class="col-sm-3">N°</th>
                                <th class="col-sm-3">Prix</th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                                @foreach($inscriptions as $inscription)
                                    <tr>
                                        <td>
                                            @if($inscription->inscrit)
                                                <p><strong>{!! $inscription->inscrit->name !!}</strong></p>
                                                <p>{!! $inscription->inscrit->email !!}</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if($inscription->participant)
                                                {!! $inscription->participant->name  !!}
                                            @endif
                                        </td>
                                        <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                        <td>{{ $inscription->price->price_cents }} CHF</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($inscriptions instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{ $inscriptions->links() }}
                        @endif

                    @endif


                </div>
            </div>

        </div>
    </div>

@stop