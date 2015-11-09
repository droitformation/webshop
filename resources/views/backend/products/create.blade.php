@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-9">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/product') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter produit</h4>
                </div>
                <form action="{{ url('admin/product') }}" method="POST" class="form-horizontal">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Poids</label>
                            <div class="col-sm-3 col-xs-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="weight">
                                    <span class="input-group-addon">grammes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="price">
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer le produit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop