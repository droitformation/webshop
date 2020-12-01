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
    <div class="col-md-12 col-xs-12" id="appComponent">

        <div class="panel panel-default">
            <div class="panel-body">

                <form action="{{ url('admin/avis') }}" method="get">{!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label"><strong>Ordre</strong></label>
                                <div>
                                    <label class="radio-inline"><input type="radio" value="alpha" {{ isset($sort) && $sort == 'alpha' || old('sort') == 'alpha' || !isset($sort) ? 'checked' : '' }} name="sort"> Alphabétique</label>
                                    <label class="radio-inline"><input type="radio" value="type" {{ isset($sort) && $sort == 'type' || old('sort') == 'type' ? 'checked' : '' }} name="sort"> Type</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <button class="btn btn-primary" type="submit">Envoyer </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(!$avis->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-1"><strong>Action</strong></div>
                        <div class="col-md-6"><strong>Question</strong></div>
                        <div class="col-md-3"><strong>Type</strong></div>
                        <div class="col-md-2"></div>
                    </div>
                    <hr/>
                        <question-row :avis="{{ $avis }}"></question-row>
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