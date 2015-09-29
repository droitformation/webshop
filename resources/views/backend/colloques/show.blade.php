@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-9">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/colloque') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter un colloque</h4>
                </div>
                <form action="{{ url('admin/colloque') }}" method="POST" class="form-horizontal" id="wizard">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type de rabais</label>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="type" id="typeSelect">
                                    <option value="global">Sur toute la commande</option>
                                    <option value="product">Sur un ou plusieurs produits</option>
                                    <option value="shipping">Frais de port gratuit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valeur</label>
                            <div class="col-sm-3 col-xs-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="value">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date d'expiration</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control datePicker" name="expire_at">
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer un colloque</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop