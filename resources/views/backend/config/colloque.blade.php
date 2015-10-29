@extends('backend.layouts.master')
@section('content')

<div ng-app="upload"><!-- App -->
    <form action="{{ url('admin/config') }}" method="POST" class="form">
         {!! csrf_field() !!}

        <div class="row">
            <div class="col-md-6">
                <div class="panel-heading">
                    <h4><i class="fa fa-flag"></i> Messages d'erreur inscriptions</h4>
                </div>
                <div class="panel panel-danger">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><strong>Déjà inscrit</strong></label>
                            <textarea name="inscription[messages][registered]" class="form-control redactorSimple">{!! Registry::get('inscription.messages.registered')!!}</textarea>
                        </div>
                        <div class="form-group">
                            <label><strong>N'a pas payé les factures</strong></label>
                            <textarea name="inscription[messages][pending]" class="form-control redactorSimple">{!! Registry::get('inscription.messages.pending') !!}</textarea>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info pull-right">Mettre à jour</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel-heading">
                    <h4><i class="fa fa-ban"></i> Restrictions</h4>
                </div>
                <div class="panel panel-warning">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><strong>Empêcher les mauvais payeur de continuer à s'inscrire</strong></label></br>
                            <?php $restrict = Registry::get('inscription.restrict'); ?>
                            <label class="radio-inline">
                                <input type="radio" {{ ($restrict ? 'checked' : '') }} name="inscription[restrict]" value="1"> oui &nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" {{ (!$restrict ? 'checked' : '') }} name="inscription[restrict]" value="0"> non
                            </label>
                        </div>
                        <div class="form-group">
                            <label><strong>Si oui la facture est considérée comme non payée après</strong></label>
                            <div class="input-group" style="width: 130px;">
                                <input type="text" class="form-control" name="inscription[days]" value="{!! Registry::get('inscription.days')!!}">
                                <span class="input-group-addon" id="basic-addon2">jours</span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info pull-right">Mettre à jour</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div><!-- App end -->
@stop