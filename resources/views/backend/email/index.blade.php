@extends('backend.layouts.master')
@section('content')

@if($emails)

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">

                    <form class="form-horizontal" action="{{ url('admin/email') }}" method="post">{!! csrf_field() !!}
                        <h4>Période</h4>
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Du</span>
                                    <input type="text" name="start" class="form-control datePicker" value="{{ old('start') }}" placeholder="Début">
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">au</span>
                                    <input type="text" name="end" class="form-control datePicker" value="{{ old('end') }}" placeholder="Fin">
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-12 text-left">
                                <div class="btn-group">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-filter"></i> &nbsp; Rechercher</button>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-12"></div>
                            <div class="col-md-4 col-xs-12" style="min-width:130px;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="order_no" placeholder="Recherche recipient, sujet ou contenu...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>


            <div class="panel panel-primary">
                <div class="panel-body">
                    <h3>Emails envoyés</h3>
                    <table class="table">
                        <thead>
                            <th>Date</th>
                            <th>Envoyé à</th>
                            <th>Sujet</th>
                            <th>Contenu</th>
                        </thead>
                        @foreach($emails as $email)
                            <tbody>
                                <td>{{ $email->date->format('d-m-Y') }}</td>
                                <td>{{ $email->to }}</td>
                                <td>{{ $email->subject }}</td>
                                <td><button data-id="{{ $email->id }}" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#emailModal">Voir l'email</button></td>
                            </tbody>
                        @endforeach
                    </table>

                    {{ $emails->links() }}

                    <!-- Modal -->
                    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                </div>
                                <div class="modal-body"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-fermer" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endif



@stop