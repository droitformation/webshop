@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <?php $active_chunks = $colloques->chunk(4); ?>
            @include('backend.colloques.partials.colloque', ['colloques' => $active_chunks, 'color' => 'primary'])
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-info">
                <div class="panel-body">
                    <h4><i class="fa fa-shopping-cart"></i> &nbsp;Dernières commandes
                        <a class="btn btn-sky btn-sm pull-right" href="{{ url('admin/orders') }}">Voir toutes les commandes</a>
                    </h4>

                    @include('backend.orders.partials.commandes', ['orders' => $orders, 'cancelled' => false])
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-info">
                <div class="panel-body">
                    <h4><i class="fa fa-table"></i> &nbsp;Dernières inscriptions</h4>
                    <table class="table normalTable" id="" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Colloque</th>
                            <th class="col-sm-3">Déteteur</th>
                            <th class="col-sm-2">No</th>
                            <th class="col-sm-2">Prix</th>
                            <th class="col-sm-1">Date</th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                            @if(!empty($inscriptions))
                                @foreach($inscriptions as $inscription)
                                    <tr>
                                        <td>
                                            @if(!isset($inscription->groupe))
                                                <a class="btn btn-sky btn-sm" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}"><i class="fa fa-edit"></i></a>
                                                @include('backend.users.modals.edit', ['inscription' => $inscription]) <!-- Modal edit inscription -->
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($inscription->groupe))
                                                <?php $group = $inscription->groupe; ?>
                                                {{ $group->colloque->titre }}
                                            @else
                                                {{ $inscription->colloque->titre }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($inscription->inscrit))
                                                <?php $adresse = $inscription->inscrit->adresses->where('type',1)->first();?>
                                                @if(isset($adresse))
                                                    {!! isset($civilites[$adresse->civilite_id]) ? '<p><strong>'.$civilites[$adresse->civilite_id].'</strong></p>' : '' !!}
                                                @endif
                                                <p><a href="{{ url('admin/user/'.$inscription->inscrit->id) }}">{{ $adresse ? $adresse->name : '' }}</a></p>
                                                <p>{{ $inscription->inscrit->email }}</p>
                                            @else
                                                <h4><span class="label label-warning">Utilisateur ou groupe non trouvé ID: {{ $inscription->group_id ?? $inscription->user_id }}</span></h4>
                                            @endif

                                            @if(isset($inscription->groupe))
                                                <br/><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#editGroup_{{ $inscription->groupe->id }}">Changer le détenteur</a>
                                                @include('backend.inscriptions.modals.change', ['group' => $inscription->groupe]) <!-- Modal edit group -->
                                            @endif

                                        </td>
                                        <td>
                                            @if(isset($inscription->groupe))
                                                <?php $group = $inscription->groupe; ?>
                                                @if($group->inscriptions)
                                                    @foreach($group->inscriptions as $inscription)
                                                        <div class="media">
                                                            <div class="media-left">
                                                                <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <a class="btn btn-sky btn-xs" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}"><i class="fa fa-edit"></i></a>
                                                                    <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-xs deleteAction">X</button>
                                                                </form>
                                                                @include('backend.users.modals.edit', ['inscription' => $inscription]) <!-- Modal edit inscription -->
                                                            </div>
                                                            <div class="media-body">
                                                                <p><strong>{!! $inscription->participant->name !!}</strong></p>
                                                                <p>
                                                                    {{ $inscription->inscription_no }}&nbsp;
                                                                    @include('backend.partials.toggle', ['id' => $inscription->id])
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <br/><a class="btn btn-success btn-xs" data-toggle="modal" data-target="#addToGroup_{{ $inscription->groupe->id }}">Ajouter un participant</a>
                                                @include('backend.inscriptions.modals.add', ['group' => $inscription->groupe, 'colloque' => $inscription->colloque]) <!-- Modal add to group -->
                                            @else
                                                <strong>{{ $inscription->inscription_no }}</strong> &nbsp;
                                                @include('backend.partials.toggle', ['id' => $inscription->id])
                                            @endif
                                        </td>
                                        <td>{{ isset($inscription->groupe) ? $group->price : $inscription->price_cents }} CHF</td>
                                        <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
                                    </tr>

                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>


@stop