@extends('backend.layouts.master')
@section('content')

   <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body" id="appComponent">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Choisir un fichier</button>
                    <manager :thumbs="{{ json_encode(['products','uploads']) }}"></manager>
                </div>
            </div>

        </div>
    </div>

@stop