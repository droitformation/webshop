@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/user') }}" method="post" id="" class="form-horizontal">{!! csrf_field() !!}

                <div class="panel-body event-info">
                    <h3>Ajouter un compte/utilisateur</h3>

                    <div class="well well-sm" style="padding:10px;background: #f5f5f5;">
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Prénom</label>
                            <div class="col-sm-6">
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="message" class="col-sm-3 control-label">Nom</label>
                            <div class="col-sm-6">
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-push-3 col-md-6">
                            <p style="margin-bottom: 15px;">
                                <strong class="text-danger">OU</strong> &nbsp;&nbsp;
                                <span class="text-muted">(Prénom et nom sont pris en compte en premier si existant)</span>
                            </p>
                        </div>
                    </div>

                    <div class="well well-sm" style="padding:10px;background: #f5f5f5;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="message" class="col-sm-3 control-label">Entreprise</label>
                            <div class="col-sm-6">
                                <input type="text" name="company" id="company" value="{{ old('company') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-6">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Mot de passe</label>
                        <div class="col-sm-6">
                            <input type="password" name="password" id="password" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong>Administrateur</strong></label>
                        <div class="col-sm-6">
                            <label class="radio-inline"><input type="radio" name="role" value="1"> Oui</label>
                            <label class="radio-inline"><input type="radio" name="role" value="0" checked> Non</label>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary" id="createUser" type="submit">Envoyer</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
<!-- end row -->

@stop