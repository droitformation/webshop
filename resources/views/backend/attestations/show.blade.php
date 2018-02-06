@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <p><a href="{{ url('admin/colloque/'.$attestation->colloque_id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <form action="{{ url('admin/attestation/'.$attestation->id) }}" method="POST" class="form-horizontal">
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                <div class="panel panel-midnightblue">
                    <div class="panel-body">

                        <h4>Info pour attestation</h4>
                        <p>{{ $attestation->colloque->titre }}</p>

                        <hr/>

                        <div class="form-group">
                            <label for="titre" class="col-sm-3 control-label">Titre du responsable</label>
                            <div class="col-sm-5">
                                {!! Form::text('title', $attestation->title , ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                            <div class="col-sm-4"><p class="help-block">Requis</p></div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Signature</label>
                            <div class="col-sm-5">
                                {!! Form::text('signature', $attestation->signature , ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                            <div class="col-sm-4"><p class="help-block">Requis</p></div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Téléphone</label>
                            <div class="col-sm-5">
                                {!! Form::text('telephone', $attestation->telephone , ['class' => 'form-control' ]) !!}
                            </div>
                            <div class="col-sm-4"><p class="help-block">La valeur par défaut est celui indiqué dans la configuration du shop</p></div>
                        </div>

                        <div class="form-group">
                            <label for="soustitre" class="col-sm-3 control-label">Lieu</label>
                            <div class="col-sm-5">
                                {!! Form::text('lieu', $attestation->lieu , ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-4"><p class="help-block">La valeur par défaut est celui indiqué dans le colloque</p></div>
                        </div>

                        <div class="form-group">
                            <label for="sujet" class="col-sm-3 control-label">Organisateur</label>
                            <div class="col-sm-5">
                                {!! Form::text('organisateur', $attestation->organisateur , ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-4"><p class="help-block">La valeur par défaut est celui indiqué dans le colloque</p></div>
                        </div>

                        <div class="form-group">
                            <label for="comment" class="col-sm-3 control-label">Remarques</label>
                            <div class="col-sm-5">
                                <textarea name="comment" cols="50" rows="4" class="redactorSimple form-control">{!! $attestation->comment !!}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer mini-footer ">
                        <div class="col-sm-3">
                            <input type="hidden" name="id" value="{{ $attestation->id }}">
                            <input type="hidden" name="colloque_id" value="{{ $attestation->colloque_id }}">
                        </div>
                        <div class="col-sm-7">
                            <button class="btn btn-primary" id="updateAttestation" type="submit">Envoyer</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

@stop