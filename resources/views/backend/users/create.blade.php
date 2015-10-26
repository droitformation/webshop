@extends('layouts.admin')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            {!! Form::open(array(
                'method' => 'POST',
                'class'  => 'form-validation form-horizontal',
                'url'    => array('admin/user')))
            !!}

            <div class="panel-heading">
                <h4>Ajouter un auteur</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Nom</label>
                    <div class="col-sm-3">
                        {!! Form::text('name', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-3">
                        {!! Form::text('email', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Rôle</label>
                    <div class="col-lg-3 col-sm-5 col-xs-9">
                        <select class="form-control" name="role">
                            <option selected value="abonne">Abonné RJN</option>
                            <option value="invite">Invité (consulter le site sans abo)</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Numéro (pour abonné)</label>
                    <div class="col-sm-3">
                        {!! Form::text('numero', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Mot de passe</label>
                    <div class="col-sm-3">
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>
<!-- end row -->

@stop