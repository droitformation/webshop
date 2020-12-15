@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-4"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/sondage') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->

<div id="appComponent">
    <create-sondage :avis="{{ $avis }}" :sondage="{{ $sondage }}" :current="{{ $sondage->avis_vue }}">
        <template v-slot:update>

            <div class="row">
                <div class="col-md-9">
                    @include('backend.sondages.partials.update-form')
                </div>
                <div class="col-md-3">
                    <?php $url = base64_encode(json_encode(['sondage_id' => $sondage->id, 'email' => config('mail.from.address'),'isTest'  => 1])); ?>

                    <a target="_blank" href="{{ url('reponse/create/'.$url) }}" class="btn btn-success btn-block"><i class="fa fa-eye"></i> &nbsp;Prévisualiser le sondage</a>
                    <a class="btn btn-info btn-block" href="{{ url('admin/reponse/'.$sondage->id) }}"><i class="fa fa-bullhorn"></i> &nbsp;Voir les réponses</a>

                    <a href="#" class="btn btn-inverse btn-block" data-toggle="modal" data-target="#updateModele"><i class="fa fa-list"></i> &nbsp; Utiliser un modèle</a>
                    @include('backend.sondages.partials.modele',['sondage' => $sondage])

                    @if($sondage->colloque_id && isset($sondage->liste))
                        <form action="{{ url('admin/sondage/updateList') }}" style="margin-top: 10px;" method="POST">{!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{ $sondage->id }}" />
                            <input type="hidden" name="colloque_id" value="{{ $sondage->colloque_id }}" />
                            <div class="well well-sm">
                                <p>Liste crée : <strong>{{ $sondage->liste->title }}</strong></p>
                                <p><i>Nombre de participants:</i> &nbsp;<strong>{{ $sondage->liste->emails->count() }}</strong></p>
                                <p><i>Dernière mise à jour: </i> &nbsp;<strong>{{ $sondage->liste->updated_at->formatLocalized('%d %b %Y') }}</strong></p>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-users"></i> &nbsp;Màj la liste des participants</button>
                        </form>
                    @elseif($sondage->colloque_id)
                        <form action="{{ url('admin/sondage/createList') }}" style="margin-top: 10px;" method="POST">{!! csrf_field() !!}
                            <input type="hidden" name="colloque_id" value="{{ $sondage->colloque_id }}" />
                            <button class="btn btn-warning btn-block" type="submit"><i class="fa fa-users"></i> &nbsp;Créer une liste de participants</button>
                        </form>
                    @endif
                </div>
            </div>
        </template>
    </create-sondage>
</div>

@stop