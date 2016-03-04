@extends('backend.layouts.master')
@section('content')

<div ng-app="upload"><!-- App -->

    <form action="{{ url('admin/config') }}" method="POST" class="form" enctype="multipart/form-data" ng-controller="UploadController as ctr"
          flow-init="{query: {'path' : 'files/main', 'id' : 'inscription_logo', '_token': '<?php echo csrf_token(); ?>' }}"
          flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
          flow-files-submitted="$flow.upload()">
        {!! csrf_field() !!}

        <div class="row">
            <div class="col-md-6">

                <h4><i class="fa fa-home"></i> &nbsp;Adresse générale pour facture colloques</h4>
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="form-group col-md-4">
                            <label><strong>Logo</strong></label>
                            <div style="max-width: 200px;">
                                <div class="uploadBtn">
                                    <span class="btn btn-xs btn-info"    ng-hide="$flow.files.length"    flow-btn flow-attrs="{accept:'image/*'}">Selectionner image</span>
                                    <span class="btn btn-xs btn-warning" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Changer</span>
                                    <span class="btn btn-xs btn-danger"  ng-show="$flow.files.length"  ng-click="$flow.cancel()">Supprimer</span>
                                </div>
                                <div class="thumbnail big" ng-if="$flow.files.length">
                                    <img style="max-height: 180px;" flow-img="$flow.files[0]" />
                                    <input type="hidden" name="inscription[infos][logo]" id="inscription_logo" value="">
                                </div>

                                <div class="thumbnail big" ng-if="!$flow.files.length">
                                    <?php $logo = Registry::get('inscription.infos.logo'); ?>
                                    <?php $logo = (!empty($logo) ? asset('files/main/'.$logo) : 'http://www.placehold.it/160x180/EFEFEF/AAAAAA&text=Logo'); ?>
                                    <img style="max-width: 100%;" src="{{ $logo }}" />
                                    <input type="hidden" name="inscription[infos][logo]" value="{{ Registry::get('inscription.infos.logo') }}">
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><strong>Nom</strong></label>
                                <input type="text" class="form-control" name="inscription[infos][nom]" value="{!! Registry::get('inscription.infos.nom')!!}">
                            </div>
                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input type="text" class="form-control" name="inscription[infos][email]" value="{!! Registry::get('inscription.infos.email')!!}">
                            </div>
                            <div class="form-group">
                                <label><strong>Adresse</strong></label>
                                <textarea name="inscription[infos][adresse]" class="form-control redactorSimple">{!! Registry::get('inscription.infos.adresse') !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info btn-sm  pull-right">Mettre à jour</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4><i class="fa fa-paper-plane"></i> &nbsp;Infos email de confirmation</h4>
                <div class="panel panel-green">
                    <div class="panel-body">

                        <div class="form-group">
                            <label><strong>IBAN</strong></label>
                            <input type="text" class="form-control" name="inscription[infos][iban]" value="{!! Registry::get('inscription.infos.iban') !!}">
                        </div>

                        <div class="form-group">
                            <label><strong>BIC</strong></label>
                            <input type="text" class="form-control" name="inscription[infos][bic]" value="{!! Registry::get('inscription.infos.bic')!!}">
                        </div>

                        <div class="form-group">
                            <label><strong>Désistement</strong></label>
                            <textarea name="inscription[infos][desistement]" class="form-control redactorSimple">{!! Registry::get('inscription.infos.desistement') !!}</textarea>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info btn-sm  pull-right">Mettre à jour</button>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <h4><i class="fa fa-qrcode"></i> &nbsp;Ajouter un QRcode</h4>
                <div class="panel panel-green">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><strong>Pour scanner les participants</strong></label></br>
                            <?php $qrcode = Registry::get('inscription.qrcode'); ?>
                            <label class="radio-inline"><input type="radio" {{ ($qrcode ? 'checked' : '') }} name="inscription[qrcode]" value="1"> oui &nbsp;</label>
                            <label class="radio-inline"><input type="radio" {{ (!$qrcode ? 'checked' : '') }} name="inscription[qrcode]" value="0"> non</label>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info btn-sm  pull-right">Mettre à jour</button>
                    </div>
                </div>

                <h4><i class="fa fa-ban"></i> &nbsp;Restrictions</h4>
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
                        <button type="submit" class="btn btn-info btn-sm  pull-right">Mettre à jour</button>
                    </div>
                </div>

            </div>
            <div class="col-md-7">
                
                <h4><i class="fa fa-flag"></i> &nbsp;Messages d'erreur inscription</h4>
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
                        <button type="submit" class="btn btn-info btn-sm  pull-right">Mettre à jour</button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div><!-- App end -->
@stop