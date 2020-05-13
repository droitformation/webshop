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
                <form action="{{ url('admin/rabais/'.$rabais->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Editer rabais</h4>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type de rabais</label>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="type" id="rabaisSelect">
                                    <option {{ ($rabais->type == 'global' ? 'selected' : '') }} value="global">Sur n'importe quel colloque</option>
                                    <option {{ ($rabais->type == 'colloque' ? 'selected' : '') }} value="colloque">Compte choisi</option>
                                </select>
                            </div>
                        </div>

                        <?php $choices = $rabais->comptes->pluck('id')->all(); ?>

                        <div class="form-group" id="colloqueSelect" style="{{ ($rabais->type == 'colloque' ? 'display:block;' : 'display:none;') }}">
                            <label class="col-sm-3 control-label">Choix des comptes concernés (optionnel)</label>
                            <div class="col-sm-9 col-xs-12">
                                @if(!$comptes->isEmpty())
                                    <select name="compte_id[]" multiple="multiple" id="multi-select">
                                        @foreach($comptes as $compte)
                                            <option {{ (in_array($compte->id,$choices) ? 'selected' : '') }} value="{{ $compte->id }}">{{ $compte->centre }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $rabais->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contenu" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-7">
                                <textarea name="description" class="form-control">{!! $rabais->description !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valeur</label>
                            <div class="col-sm-5 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $rabais->value }}" name="value">
                                    <span class="input-group-addon" id="val_addon">CHF</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date d'expiration<br><small class="text-muted">(optionnel)</small></label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control datePicker" value="{{ $rabais->expire_at->format('Y-m-d') }}" name="expire_at">
                            </div>
                        </div>

                        <input type="hidden" value="{{ $rabais->id }}" name="id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop