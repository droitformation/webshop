@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Abonnés aux newsletter</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/subscriber/create') }}" class="btn btn-green" id="addSubscriber"><i class="fa fa-plus"></i> &nbsp;Ajouter un abonné</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-info">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table abonnes_table" data-newsletter="1499252">
                        <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-2">Status</th>
                                <th class="col-sm-2">Activé le</th>
                                <th class="col-sm-2">Email</th>
                                <th class="col-sm-3">Abonnements</th>
                                <th class="col-sm-1"></th>
                            </tr>
                        </thead>
                        <tbody class="selects"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@stop
