@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/analyses/'.$analyse->site_id)  !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
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

            <div class="panel-body event-info" ng-app="selection" id="main" data-site="{{ $analyse->site_id }}">
                <h4>&Eacute;diter l'analyse</h4>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre alternatif (remplace analyse de l'arrêt...)</label>
                    <div class="col-sm-3">
                        {!! Form::text('title', $analyse->title , ['class' => 'form-control'] ) !!}
                    </div>
                </div>

                <?php $authors = (isset($analyse->authors) ? $analyse->authors->pluck('id')->all() : []); ?>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Auteurs</label>
                    <div class="col-sm-3">
                        <select multiple class="form-control" required id="author" name="author_id[]">
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
                    <label for="message" class="col-sm-3 control-label">Appartient au site</label>
                    <div class="col-sm-3">
                        @if(!$sites->isEmpty())
                            <select class="form-control" required name="site_id">
                                <option value="">Appartient au site</option>
                                @foreach($sites as $select)
                                    <option {{ $select->id == $analyse->site_id ? 'selected' : '' }}   value="{{ $select->id }}">{{ $select->nom }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Date de publication</label>
                    <div class="col-sm-2">
                        <input type="text" name="pub_date" required class="form-control datePicker" value="{{ $analyse->pub_date->format('Y-m-d') }}">
                    </div>
                </div>

                @if(!empty($analyse->file ))
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Fichier</label>
                    <div class="col-sm-7">
                        <div class="list-group">
                            <div class="list-group-item">
                                <?php $rand = rand(200,1000); ?>
                                <a target="_blank" href="{{ secure_asset('files/analyses/'.$analyse->file.'?'.$rand) }}">
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
                        {!! Form::textarea('abstract', $analyse->abstract , array('class' => 'form-control', 'cols' => '50' , 'rows' => '4' ,'required' => 'required')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Résumé (sur page des auteurs)</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('remarque', $analyse->remarque , array('class' => 'form-control', 'cols' => '50' , 'rows' => '4')) !!}
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
                                        <li ng-repeat="item in list  | orderBy:'sort'"
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