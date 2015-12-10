@extends('backend.layouts.master')
@section('content')

<div ng-app="upload"><!-- App -->

    <form action="{{ url('admin/config') }}" method="POST" class="form" enctype="multipart/form-data" ng-controller="UploadController as ctr"
          flow-init="{query: {'path' : 'files/main', 'id' : 'abo_logo', '_token': '<?php echo csrf_token(); ?>' }}"
          flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
          flow-files-submitted="$flow.upload()">
         {!! csrf_field() !!}

        <div class="row">

            <div class="col-md-6">
                <h4><i class="fa fa-home"></i> &nbsp;Adresse générale pour facture abo</h4>
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
                                    <input type="hidden" id="abo_logo" name="abo[infos][logo]" value="">
                                </div>

                                <div class="thumbnail big" ng-if="!$flow.files.length">
                                    <?php $logo = Registry::get('abo.infos.logo'); ?>
                                    <?php $logo = (!empty($logo) ? asset('files/main/'.$logo) : 'http://www.placehold.it/160x180/EFEFEF/AAAAAA&text=Logo'); ?>
                                    <img style="max-width: 100%;" src="{{ $logo }}" />
                                    <input type="hidden" name="abo[infos][logo]" value="{{ Registry::get('abo.infos.logo') }}">
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><strong>Adresse</strong></label>
                                <textarea name="abo[infos][adresse]" class="form-control redactorSimple">{!! Registry::get('abo.infos.adresse') !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label><strong>Compte abonnements</strong></label>
                                <input type="text" class="form-control" name="abo[compte]" value="{!! Registry::get('abo.compte')!!}">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info pull-right">Mettre à jour</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4><i class="fa fa-comment"></i> &nbsp;Messages</h4>
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><strong>Communications</strong></label>
                            <textarea class="form-control redactorSimple" name="abo[message]">{!! Registry::get('abo.message')!!}</textarea>
                        </div>
                        <div class="form-group">
                            <label><strong>Conditions de paiement</strong></label>
                            <div class="input-group" style="width: 150px;">
                                <input type="text" class="form-control" name="abo[days]" value="{!! Registry::get('abo.days')!!}">
                                <span class="input-group-addon" id="basic-addon2">jours net</span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer"><button type="submit" class="btn btn-info pull-right">Mettre à jour</button></div>
                </div>

            </div>
        </div>

    </form>
</div><!-- App end -->
@stop