@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/bloc') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if (!empty($bloc) )

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/bloc/'.$bloc->id) }}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>&Eacute;diter {{ $bloc->titre }}</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-4">
                        {!! Form::text('titre', $bloc->titre , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="bloc" class="col-sm-3 control-label">Bloc</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('bloc', $bloc->bloc , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="url" class="col-sm-3 control-label">Lien<br/>
                        <small class="text-muted">Sur l'image</small>
                    </label>
                    <div class="col-sm-7">
                        {!! Form::text('url', $bloc->url  , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                @if(!empty($bloc->image ))
                <div class="form-group">
                    <label for="image" class="col-sm-3 control-label">Ajouter une image<br/>
                        <small class="text-muted">Pour pub ou soutien</small>
                    </label>
                    <div class="col-sm-4">
                        <div class="list-group">
                            <div class="list-group-item text-center">
                                <a href="#"><img height="120" src="{{ asset('files/'.$bloc->image) }}" alt="{{ $bloc->titre }}" /></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Changer l'image</label>
                    <div class="col-sm-4">
                        <div class="list-group">
                            <div class="list-group-item">
                                {!! Form::file('file') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="type" class="col-sm-3 control-label">Type de bloc</label>
                    <div class="col-sm-4">
                       {!! Form::select('type', ['pub' => 'Publicité','texte' => 'Texte','soutien' => 'Soutien'], $bloc->type, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="position" class="col-sm-3 control-label">Position</label>
                    <div class="col-sm-4">
                        {!! Form::select('position', $positions ,$bloc->position, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Rang</label>
                    <div class="col-sm-2">
                        {!! Form::text('rang', $bloc->rang , array('class' => 'form-control') ) !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                {!! Form::hidden('id', $bloc->id ) !!}
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