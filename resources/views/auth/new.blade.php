@extends('auth.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p><a class="btn btn-info" href="{{ url('profil') }}"><i class="fa fa-user"></i> &nbsp;Retour à mon compte</a></p>

            <div class="panel panel-default">
                <div class="panel-heading">Nouveau mot de passe</div>

                <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/define') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-4 control-label">E-Mail</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Ancien mot de passe</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="old_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Nouveau mot de passe</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Envoyer</button>
                </form>
            </div>

        </div>

    </div>
</div>

@stop