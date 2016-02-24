@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/location')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!!  url('admin/location')!!}" method="POST" class="validate-form form-horizontal" data-validate="parsley" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>Ajouter un lieu</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-7">
                            <input type="text" name="name" value="" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Lien externe</label>
                        <div class="col-sm-7">
                            <input type="text" name="url" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file" class="col-sm-3 control-label">Changer la carte</label>
                        <div class="col-sm-5">
                            <div class="list-group">
                                <div class="list-group-item">
                                    {!! Form::file('file', ['required' => 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <p class="help-block">Utilisé sur le bon de participation</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Adresse</label>
                        <div class="col-sm-7">
                            {!! Form::textarea('adresse', null, ['required' => 'required','class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4']) !!}
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-7">
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop