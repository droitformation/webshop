@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/analyse')  !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ( !empty($analyse) )

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/analyse/'.$analyse->id) }}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>&Eacute;diter l'analyse de {!! $analyse->authors  !!}</h4>
            </div>
            <div class="panel-body event-info" ng-app="selection">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Auteurs</label>
                    <div class="col-sm-3">
                        {!! Form::text('authors', $analyse->authors , array('class' => 'form-control') )  !!}
                    </div>
                </div>

                <?php $authors = (isset($analyse->analyse_authors) ? $analyse->analyse_authors->lists('id')->all() : []); ?>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Auteurs</label>
                    <div class="col-sm-3">
                        <select multiple class="form-control" id="author" name="author_id[]">
                            <option value="">Choisir</option>
                            @if(!empty($auteurs))
                                @foreach($auteurs as $auteur)
                                    <option <?php echo (in_array($auteur->id,$authors) ? 'selected' : ''); ?> value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Date de publication</label>
                    <div class="col-sm-2">
                        {!! Form::text('pub_date', $analyse->pub_date->format('Y-m-d') , array('class' => 'form-control datePicker') ) !!}
                    </div>
                </div>

                @if(!empty($analyse->file ))
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Fichier</label>
                    <div class="col-sm-7">
                        <div class="list-group">
                            <div class="list-group-item">
                                <?php $rand = rand(200,1000); ?>
                                <a target="_blank" href="{{ asset('files/analyses/'.$analyse->file.'?'.$rand) }}">
                                <i class="fa fa-file"></i> &nbsp;&nbsp;{!! $analyse->file  !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Changer le fichier</label>
                    <div class="col-sm-7">
                        {!! Form::file('file')  !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Résumé</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('abstract', $analyse->abstract , array('class' => 'form-control', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Catégories</label>
                    <div class="col-sm-9">

                        <div ng-controller="MultiSelectionController as selectcat">
                            <div class="listArrets forArrets" ng-init="typeItem='categories';uidContent='{!! $analyse->id  !!}';itemContent='analyses'">
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

                <div class="form-group">
                    <label class="col-sm-3 control-label">Arrêts</label>
                    <div class="col-sm-9">

                        <div ng-controller="MultiSelectionController as selectarret">
                            <div class="listArrets forArrets" ng-init="typeItem='arrets';uidContent='{!! $analyse->id  !!}';itemContent='analyses'">
                                <div ng-repeat="(listName, list) in selectarret.models.lists">
                                    <ul class="list-arrets" dnd-list="list">
                                        <li ng-repeat="item in list"
                                            dnd-draggable="item"
                                            dnd-moved="list.splice($index, 1); logEvent('Container moved', event); selectarret.dropped(item)"
                                            dnd-effect-allowed="move"
                                            dnd-selected="models.selected = item"
                                            ng-class="{'selected': models.selected === item}" >
                                            {[{ item.reference }]}
                                            <input type="hidden" name="arrets[]" ng-if="item.isSelected" value="{[{ item.itemId }]}" />
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
                {!! Form::hidden('id', $analyse->id ) !!}
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