@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/config') }}" method="POST" class="form">
                    {!! csrf_field() !!}

                    <div class="panel-heading">
                        <h4><i class="fa fa-edit"></i> &nbsp;Configurations</h4>
                    </div>

                    <div class="panel-body">
                        <h4>Messages d'erreur inscriptions</h4>

                            <div class="form-group">
                                <label><strong>Déjà inscrit</strong></label>
                                <textarea name="inscription[messages][registered]" class="form-control redactorSimple">{!! Registry::get('inscription.messages.registered')!!}</textarea>
                            </div>

                            <hr/>
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
                                <label><strong>N'a pas payé les factures</strong></label>
                                <textarea name="inscription[messages][pending]" class="form-control redactorSimple">{!! Registry::get('inscription.messages.pending') !!}</textarea>
                            </div>

                            <div class="form-group">
                                <label><strong>La facture est considérée comme non payée après</strong></label>

                                <div class="input-group" style="width: 130px;">
                                    <input type="text" class="form-control" name="inscription[days]" value="{!! Registry::get('inscription.days')!!}">
                                    <span class="input-group-addon" id="basic-addon2">jours</span>
                                </div>
                            </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info pull-right">Mettre à jour</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop