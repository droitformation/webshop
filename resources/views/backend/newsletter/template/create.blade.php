@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">

            <form action="{{ url('admin/newsletter') }}" data-validate="parsley" method="POST" enctype="multipart/form-data" class="validate-form form-horizontal">
                {!! csrf_field() !!}
                <div class="panel-heading">
                    <h4>Ajouter une newsletter</h4>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-5">
                            {!! Form::text('titre', null , array('required' => 'required','class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Nom de la liste</label>
                        <div class="col-sm-5">
                            <select class="form-control" required name="list_id">
                                <option value="">Choix de la liste</option>
                                @if(!empty($lists))
                                    @foreach($lists as $list)
                                        <option value="{{ $list->ID }}">{{ $list->Name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Envoyé par</label>
                        <div class="col-sm-5">
                            {!! Form::text('from_name', null , array('required' => 'required','class' => 'form-control') ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Email d'envoi</label>
                        <div class="col-sm-5">
                            {!! Form::text('from_email', null , array('required' => 'required','class' => 'form-control') ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Email de retour</label>
                        <div class="col-sm-5">
                            {!! Form::text('return_email', null , array('required' => 'required','class' => 'form-control') ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Lien de désinscription</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">{{ url('/') }}/</span>
                                {!! Form::text('unsuscribe', null , array('required' => 'required','class' => 'form-control') ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Couleur principale</label>
                        <div class="col-sm-3">
                            {!! Form::text('color', '#556B2F' , array('required' => 'required','class' => 'form-control colorpicker') ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Bannière avec logos</label>
                        <div class="col-sm-6">
                            <input type="file" required name="logos">
                            <p class="help-block">Taille max 600x130px</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Bannière de titre</label>
                        <div class="col-sm-6">
                            <input type="file" required name="header">
                            <p class="help-block">Taille max 600x160px</p>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        {!! Form::hidden('preview', url('/')) !!}
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

@stop
