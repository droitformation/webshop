@extends('backend.layouts.master')
@section('content')

    <?php $civilites = $civilites->pluck('title','id')->all(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>
                        <?php $illustration = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                        <img style="height: 50px; float:left;margin-right: 15px; margin-bottom: 10px;" src="{{ asset('files/colloques/illustration/'.$illustration.'') }}" />

                        <p><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a><br/><small>{{ $colloque->event_date }}</small></p>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <h3>Desinscriptions</h3>
            <p><a href="{{ url('admin/inscription/colloque/'.$colloque->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

            <div class="panel panel-warning">
                <div class="panel-body">

                    <table class="table" id="generic" style="margin-bottom: 0px;"><!-- Start desinscriptions -->
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Nom</th>
                            <th class="col-sm-2">Participant</th>
                            <th class="col-sm-2">No</th>
                            <th class="col-sm-2">Prix</th>
                            <th class="col-sm-2">Date</th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                            @if(!empty($desinscriptions))
                                @foreach($desinscriptions as $inscription)

                                    <?php $style = ($inscription->group_id > 0 ? 'class="isGoupe"' : ''); ?>
                                    <tr {!! $style !!}>
                                        <td>

                                            <form action="{{ url('admin/inscription/restore/'.$inscription->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                                                <button data-what="Restaurer" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-warning btn-xs deleteAction">Restaurer</button>
                                            </form>

                                        </td>
                                        <td>

                                            @if(isset($inscription->inscrit))
                                                <?php $adresse = $inscription->inscrit->adresses->where('type',1)->first();?>
                                                {!! isset($civilites[$adresse->civilite_id]) ? '<p><strong>'.$civilites[$adresse->civilite_id].'</strong></p>' : '' !!}
                                                <p><a href="{{ url('admin/user/'.$inscription->inscrit->id) }}">{{ $adresse->name }}</a></p>

                                            @else
                                                <p><span class="label label-warning">Duplicata</span></p>
                                            @endif

                                        </td>
                                        <td><?php echo ($inscription->group_id > 0 ? $inscription->participant->name :''); ?></td>
                                        <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                        <td>{{ $inscription->group_id ? $inscription->groupe->price : $inscription->price_cents }} CHF</td>
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

@stop