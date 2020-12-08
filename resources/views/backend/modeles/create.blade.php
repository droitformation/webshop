@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-9">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/modele') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/modele') }}" method="POST" class="form-horizontal">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Créer un modèle pour sondage</h4><br>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-8 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-8 col-xs-6">
                                <textarea name="description" cols="20" rows="3" class="form-control"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop