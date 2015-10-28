@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/user/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4><i class="fa fa-tasks"></i> &nbsp;Utilisateur/Comptes</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table users_table" style="margin-bottom: 0px;" id="">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Nom</th>
                            <th class="col-sm-3">Email</th>
                            <th class="col-sm-3">Adresse(s)</th>
                            <th class="col-sm-2"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@stop