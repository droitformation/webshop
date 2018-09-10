@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Catalogue de questions</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/avis/create') }}" class="btn btn-success" id="addBtn"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        @if(!$avis->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">
                    @include('backend.avis.partials.liste', ['avis' => $avis])

                    <hr/>
                    <p><a class="btn btn-warning btn-sm pull-right" data-toggle="collapse" href="#hiddenTable">Questions cahées</a></p>
                </div>
            </div>

        @endif

        <div id="hiddenTable" class="collapse">
            <div class="panel panel-warning">
                <div class="panel-body">
                    <h3><i class="fa fa-times"></i> &nbsp;Questions cachées </h3>
                    @include('backend.avis.partials.liste', ['avis' => $hidden])
                </div>
            </div>
        </div>

    </div>
</div>

@stop