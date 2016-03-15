@extends('auth.layouts.master')
@section('content')

<form class="form-horizontal form-validation" role="form" method="POST" action="/auth/login">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="panel-body">
        <input type="hidden" class="form-control" name="email" value="info@droitformation.ch">
        <div class="form-group">
            <label class="col-md-4 control-label">Code d'accès</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">Entrer</button>
            </div>
        </div>
    </div>
</form>

@stop
