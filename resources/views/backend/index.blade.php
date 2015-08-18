@extends('backend.layouts.master')
@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Derniers </h4>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Volume</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a class="btn btn-sm btn-info" href="#">Voir</a></td>
                            <td>test</td>
                            <td>test</td>
                        </tr>
                        </tbody>
                    </table>
                    <p><a class="btn btn-sm btn-primary" href="#">Tous</a></p>
                </div>
            </div>
        </div>

    </div>

@stop