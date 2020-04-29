@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/rabais') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/rabais') }}" method="POST" class="form-horizontal">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Ajouter rabais</h4>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type de rabais</label>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="type" id="rabaisSelect">
                                    <option value="global">Sur n'importe quel colloque</option>
                                    <option value="colloque">Colloques choisi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="colloqueSelect" style="display:none;">
                            <label class="col-sm-3 control-label">Choix des colloques concernés (optionnel)</label>
                            <div class="col-sm-9 col-xs-12">
                                @if(!$colloques->isEmpty())
                                    <select name="colloque_id[]" multiple="multiple" id="multi-select">
                                        @foreach($colloques as $colloques)
                                            <option value="{{ $colloques->id }}">{{ $colloques->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valeur</label>
                            <div class="col-sm-5 col-xs-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="value">
                                    <span class="input-group-addon" id="val_addon">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date d'expiration<br><small class="text-muted">(optionnel)</small></label>
                            <div class="col-sm-5 col-xs-6">
                                <input type="text" class="form-control datePicker" name="expire_at">
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer un rabais</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop