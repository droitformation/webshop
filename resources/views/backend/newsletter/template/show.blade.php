@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/newsletter') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i>  &nbsp;&nbsp;Retour aux newsletter</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">

                <form action="{{ url('admin/newsletter/'.$newsletter->id) }}" data-validate="parsley" method="POST" enctype="multipart/form-data" class="validate-form form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-heading">
                        <h4>&Eacute;diter la newsletter</h4>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5">
                                {!! Form::text('titre', $newsletter->titre , array('required' => 'required','class' => 'form-control') ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Nom de la liste</label>
                            <div class="col-sm-5">
                                <select class="form-control" required name="list_id">
                                    <option value="">Choix de la liste</option>
                                    @if(!empty($lists))
                                        @foreach($lists as $list)
                                            <option {{ $newsletter->list_id == $list->ID ? 'selected' :'' }} value="{{ $list->ID }}">{{ $list->Name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Envoyé par</label>
                            <div class="col-sm-5">
                                {!! Form::text('from_name', $newsletter->from_name , array('required' => 'required','class' => 'form-control') ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Email d'envoi</label>
                            <div class="col-sm-5">
                                {!! Form::text('from_email', $newsletter->from_email , array('required' => 'required','class' => 'form-control') ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Email de retour</label>
                            <div class="col-sm-5">
                                {!! Form::text('return_email', $newsletter->return_email , array('required' => 'required','class' => 'form-control') ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Lien de désinscription</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">{{ url('/') }}/</span>
                                    {!! Form::text('unsuscribe', $newsletter->unsuscribe  , array('required' => 'required','class' => 'form-control') ) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Couleur principale</label>
                            <div class="col-sm-3">
                                {!! Form::text('color',  $newsletter->color , array('required' => 'required','class' => 'form-control colorpicker') ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Bannière avec logos</label>
                            <div class="col-sm-6">
                                <p><img style="border: 1px solid #ddd;" src="{{ $newsletter->banniere_logos }}" alt="Logos" /></p>
                                <input type="file" name="logos">
                                <p class="help-block">Taille max 600x130px</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Bannière de titre</label>
                            <div class="col-sm-6">
                                <p><img style="border: 1px solid #ddd;" src="{{ $newsletter->banniere_header }}" alt="Header" /></p>
                                <input type="file" name="header">
                                <p class="help-block">Taille max 600x160px</p>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer mini-footer ">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <input type="hidden" name="id" value="{{ $newsletter->id }}">
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop
