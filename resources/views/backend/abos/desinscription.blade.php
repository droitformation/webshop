@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-warning">
                <div class="panel-body">

                    <img class="thumbnail" style="height: 50px; float:left; margin-right: 15px;padding: 2px;" src="{{ asset('files/products/'.$abo->current_product->image) }}" />
                    <h3 style="margin-bottom:0;line-height:24px">Abo</h3>
                    <p style="margin-bottom: 15px;">&Eacute;dition {{ $abo->title }}</p>
                    <h3 style="margin-bottom: 20px;">Résiliations</h3>

                    <table class="table simple-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Action</th>
                            <th class="col-md-1">Numéro</th>
                            <th class="col-md-4">Nom</th>
                            <th class="col-md-4">Entreprise</th>
                            <th class="col-md-1">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$abo->resilie->isEmpty())
                            @foreach($abo->resilie as $abonnement)
                                <tr>
                                    <td>
                                        <form action="{{ url('admin/abonnement/restore/'.$abonnement->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                                            <button id="restore_{{ $abonnement->id }}" data-what="Restaurer" data-action="N°: {{ $abonnement->numero }}" class="btn btn-warning btn-xs deleteAction">Restaurer</button>
                                        </form>
                                    </td>
                                    <td>{{ $abonnement->numero }}</td>
                                    <td>
                                        @if($abonnement->user)
                                            {{ $abonnement->user->name }}
                                        @elseif($abonnement->originaluser)
                                            {{ $abonnement->originaluser->name }}
                                        @else
                                            <p><span class="label label-warning">Duplicata</span></p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($abonnement->user)
                                            {{ $abonnement->user->company }}
                                        @elseif($abonnement->originaluser)
                                            {{ $abonnement->originaluser->company }}
                                        @else
                                            <p><span class="label label-warning">Duplicata</span></p>
                                        @endif
                                    </td>
                                    <td>Résilié</td>
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