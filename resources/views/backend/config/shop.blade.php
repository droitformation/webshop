@extends('backend.layouts.master')
@section('content')

<div ng-app="upload"><!-- App -->
    <form action="{{ url('admin/config') }}" method="POST" class="form" enctype="multipart/form-data"
          flow-init="{query: {'path' : 'files/main','id' : 'shop_logo', '_token': '<?php echo csrf_token(); ?>' }}"
          flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
          flow-files-submitted="$flow.upload()">
         {!! csrf_field() !!}

        <div class="row">

            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><i class="fa fa-cart"></i> Adresse générale pour facture</h4>
                    </div>
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
                                    <input type="hidden" name="shop[infos][logo]" id="shop_logo" value="">
                                </div>

                                <div class="thumbnail big" ng-if="!$flow.files.length">
                                    <?php $logo = Registry::get('shop.infos.logo'); ?>
                                    <?php $logo = (!empty($logo) ? asset('files/main/'.$logo) : 'http://www.placehold.it/160x180/EFEFEF/AAAAAA&text=Logo'); ?>
                                    <img style="max-width: 100%;" src="{{ $logo }}" />
                                    <input type="hidden" name="shop[infos][logo]" value="{{ Registry::get('shop.infos.logo') }}">
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><strong>Nom</strong></label>
                                <input type="text" class="form-control" name="shop[infos][nom]" value="{!! Registry::get('shop.infos.nom')!!}">
                            </div>
                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input type="text" class="form-control" name="shop[infos][email]" value="{!! Registry::get('shop.infos.email')!!}">
                            </div>
                            <div class="form-group">
                                <label><strong>Adresse</strong></label>
                                <textarea name="shop[infos][adresse]" class="form-control redactorSimple">{!! Registry::get('shop.infos.adresse') !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label><strong>Compte postal vente livres</strong></label>
                                <input type="text" class="form-control" name="shop[compte][livre]" value="{!! Registry::get('shop.compte.livre')!!}">
                            </div>
                            <div class="form-group">
                                <label><strong>N° de TVA</strong></label>
                                <input type="text" class="form-control" name="shop[infos][tva]" value="{!! Registry::get('shop.infos.tva')!!}">
                            </div>
                            <div class="form-group">
                                <label><strong>TVA taux réduit</strong></label>
                                <div class="input-group col-md-3">
                                    <input type="text" class="form-control" name="shop[infos][taux_reduit]" value="{!! Registry::get('shop.infos.taux_reduit')!!}">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><strong>TVA taux normal</strong></label>
                                <div class="input-group col-md-3">
                                    <input type="text" class="form-control" name="shop[infos][taux_normal]" value="{!! Registry::get('shop.infos.taux_normal')!!}">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info pull-right">Mettre à jour</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>

        </div>

    </form>
</div><!-- App end -->
@stop