@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/shipping') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/shipping/'.$shipping->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Editer le frais de port</h4>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Calcul du prix</label>
                            <div class="col-sm-3 col-xs-5">
                                <select class="form-control" name="type" id="typeSelect">
                                    <option {{ ($shipping->type == 'poids' ?  'selected' : '') }}  value="poids">Poids</option>
                                    <option {{ ($shipping->type == 'gratuit' ? 'selected' : '') }} value="gratuit">Gratuit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $shipping->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Poids maximum</label>
                            <div class="col-sm-5 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $shipping->value }}" name="value">
                                    <span class="input-group-addon">grammes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $shipping->price_cents }}" name="price">
                            </div>
                        </div>

                        <input type="hidden" value="{{ $shipping->id }}" name="id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop