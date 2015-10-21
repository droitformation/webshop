@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/contenu') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/contenu') }}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>Ajouter un contenu</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-4">
                        {!! Form::text('titre', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('contenu', null, array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="url" class="col-sm-3 control-label">Lien<br/>
                        <small class="text-muted">Sur l'image</small>
                    </label>
                    <div class="col-sm-7">
                        {!! Form::text('url', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Ajouter une image<br/>
                        <small class="text-muted">Pour pub ou soutien</small>
                    </label>
                    <div class="col-sm-4">
                        <div class="list-group">
                            <div class="list-group-item">
                                {!! Form::file('file') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="type" class="col-sm-3 control-label">Type de contenu</label>
                    <div class="col-sm-4">
                        {!! Form::select('type', array('pub' => 'Publicité','texte' => 'Texte','soutien' => 'Soutien') ,null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="position" class="col-sm-3 control-label">Position</label>
                    <div class="col-sm-4">
                        {!! Form::select('position', $positions, null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Rang</label>
                    <div class="col-sm-2">
                        {!! Form::text('rang', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </div>
            </div>

            </form>
        </div>
    </div>

</div>
<!-- end row -->

@stop