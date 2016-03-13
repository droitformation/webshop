@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/compte')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">
    <div class="col-md-8">

        <div class="panel panel-midnightblue">
            <form action="{!! url('admin/compte')!!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}
                <div class="panel-body event-info">

                    <h4>Ajouter un compte</h4>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Motif</label>
                        <div class="col-sm-9">
                            <textarea name="motif" required cols="50" rows="4" class="redactorSimple form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Adresse</label>
                        <div class="col-sm-9">
                            <textarea name="adresse" required cols="50" rows="4" class="redactorSimple form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Compte</label>
                        <div class="col-sm-9">
                            <input type="text" name="compte" value="" class="form-control" required>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>

           </form>
        </div>

    </div>
</div>
<!-- end row -->

@stop