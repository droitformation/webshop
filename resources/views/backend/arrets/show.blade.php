@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
         <p><a class="btn btn-default" href="{!! url('admin/arrets/'.$arret->site_id) !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

@if ( !empty($arret) )

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!! url('admin/arret/'.$arret->id) !!}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

            <div class="panel-body event-info">
                <h4>&Eacute;diter {!! $arret->reference !!}</h4>
                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Site</label>
                    <div class="col-sm-3">
                        @if(!$sites->isEmpty())
                            <select class="form-control" name="site_id">
                                <option value="">Appartient au site</option>
                                @foreach($sites as $site)
                                    <option {{ $arret->site_id == $site->id ? 'selected' : '' }} value="{{ $site->id }}">{{ $site->nom }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Référence</label>
                    <div class="col-sm-5">
                        {!! Form::text('reference', $arret->reference , array('class' => 'form-control') ) !!}
                        <br/>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" <?php echo ($arret->dumois ? 'checked' : ''); ?> name="dumois"> Arrêt du mois
                            </label>
                        </div>
                        <p class="help-block">Attache l'analyse à l'arrêt dans la newsletter</p>

                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Date de publication</label>
                    <div class="col-sm-2">
                        {!! Form::text('pub_date', $arret->pub_date->format('Y-m-d') , array('class' => 'form-control datePicker') ) !!}
                    </div>
                </div>

                @if(!empty($arret->file ))
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Fichier</label>
                    <div class="col-sm-7">
                        <div class="list-group">
                            <div class="list-group-item">
                                <?php $rand = rand(200,1000); ?>
                                <a target="_blank" href="{!! secure_asset('files/arrets/'.$arret->file.'?'.$rand) !!}">
                                    <i class="fa fa-file"></i> &nbsp;&nbsp;
                                    {!! $arret->file !!}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Changer le fichier</label>
                    <div class="col-sm-7">
                        {!! Form::file('file') !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Résumé</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('abstract', $arret->abstract , array('class' => 'form-control', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Texte</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('pub_text', $arret->pub_text , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Catégories</label>

                    <div class="col-sm-9" id="main" ng-app="selection" data-site="{{ $arret->site_id }}">
                        <div ng-controller="MultiSelectionController as selectcat">
                            <div class="listArrets forArrets" ng-init="typeItem='categories';uidContent='{!! $arret->id !!}';itemContent='arrets'">
                                <div ng-repeat="(listName, list) in selectcat.models.lists">
                                    <ul class="list-arrets" dnd-list="list">
                                        <li ng-repeat="item in list"
                                            dnd-draggable="item"
                                            dnd-moved="list.splice($index, 1); logEvent('Container moved', event); selectcat.dropped(item)"
                                            dnd-effect-allowed="move"
                                            dnd-selected="models.selected = item"
                                            ng-class="{'selected': models.selected === item}" >
                                            {[{ item.title }]}
                                            <input type="hidden" name="categories[]" ng-if="item.isSelected" value="{[{ item.itemId }]}" />
                                        </li>
                                    </ul>
                                </div>
                                <div view-source="simple"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="panel-footer mini-footer ">
                {!! Form::hidden('id', $arret->id )!!}
                {!! Form::hidden('user_id', \Auth::user()->id )!!}
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <button class="btn btn-primary" type="submit">Envoyer </button>
                </div>
            </div>

            </form>

        </div>
    </div>

@endif

</div>
<!-- end row -->

@stop