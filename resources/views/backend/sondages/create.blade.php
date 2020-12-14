@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/sondage')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-9">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/sondage') }}" method="POST" enctype="multipart/form-data" class="validate-form form-horizontal" data-validate="parsley">{!! csrf_field() !!}

                <div class="panel panel-midnightblue">
                    <div class="panel-body">

                        <h3>Sondage</h3>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><strong>Type de sondage</strong></label>
                            <div class="col-sm-8">
                                <label class="radio-inline"><input type="radio" class="typeSondage" name="marketing" value="1"> Sondage marketing</label>
                                <label class="radio-inline"><input type="radio" class="typeSondage" name="marketing" checked value=""> Sondage pour colloque</label>
                            </div>
                        </div>

                        <div class="form-group" id="sondageColloque">
                            <label for="message" class="col-sm-3 control-label">Colloque</label>
                            <div class="col-sm-6">
                                <select autocomplete="off" name="colloque_id" class="form-control">
                                    <option value="">Choisir le colloque</option>
                                    @if(!$colloques->isEmpty())
                                        @foreach($colloques as $colloque)
                                            <option {{ (old('colloque_id') == $colloque->id ) ? 'selected' : '' }} value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="sondageColloque">
                            <label for="message" class="col-sm-3 control-label">Modèle de sondage</label>
                            <div class="col-sm-6">
                                <select autocomplete="off" name="colloque_id" class="form-control">
                                    <option value="">Choisir</option>
                                    @if(!$modeles->isEmpty())
                                        @foreach($modeles as $modele)
                                            <option {{ (old('modele_id') == $modele->id ) ? 'selected' : '' }} value="{{ $modele->id }}">{{ $modele->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div id="sondageMarketing" style="display: none;">
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Titre</label>
                                <div class="col-sm-6">
                                    <input type="text" name="title" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Organisateur</label>
                                <div class="col-sm-6">
                                    <input type="text" name="organisateur" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="file" class="col-sm-3 control-label">Bannière image <br><small>(max 910px85px)</small></label>
                                <div class="col-sm-6">
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            {!!  Form::file('file')!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Description du sondage marketing</label>
                                <div class="col-sm-6">
                                    <textarea name="description" class="form-control redactorSimple"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Texte de l'email<br><small>Si vide la description est prise.</small></label>
                                <div class="col-sm-6">
                                    <textarea name="description" class="form-control redactorSimple"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Signature</label>
                                <div class="col-sm-6">
                                    <input type="text" name="signature" value="" placeholder="Le secrétariat de la Faculté de droit" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Valide jusqu'au</label>
                            <div class="col-sm-3">
                                <input type="text" name="valid_at" value="" class="form-control datePicker required">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer mini-footer ">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop