@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/arrets/'.$site) !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>

<!-- start row -->
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!! url('admin/arret') !!}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

            <div class="panel-body event-info">
                <h4>Créer arrêt</h4>
                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Site</label>
                    <div class="col-sm-3">
                        @if(!$sites->isEmpty())
                            <select class="form-control" name="site_id">
                                <option value="">Appartient au site</option>
                                @foreach($sites as $select)
                                    <option {{ $select->id == $site ? 'selected' : '' }}  value="{{ $select->id }}">{{ $select->nom }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Référence</label>
                    <div class="col-sm-5">
                        {!! Form::text('reference', null , array('class' => 'form-control') ) !!}
                        <br/>
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('dumois', '1') !!} Arrêt du mois
                            </label>
                        </div>
                        <p class="help-block">Attache l'analyse à l'arrêt dans la newsletter</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Date de publication</label>
                    <div class="col-sm-2">
                        {!! Form::text('pub_date', null , array('class' => 'form-control datePicker') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Fichier</label>
                    <div class="col-sm-7">
                        {!! Form::file('file') !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Résumé</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('abstract', null , array('class' => 'form-control', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Texte</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('pub_text', null , array('class' => 'form-control redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Catégories</label>

                    <div class="col-sm-9" ng-app="selection">

                        <div ng-controller="MultiSelectionController as selectcat">
                            <div class="listArrets forArrets" ng-init="typeItem='categories'">
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
                <div class="col-sm-3">
                    <input type="hidden" name="site_id" value="{{ $site }}">
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary" type="submit">Envoyer </button>
                </div>
            </div>
            </form>
        </div>
    </div>

</div>
<!-- end row -->

@stop