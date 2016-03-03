@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/organisateur')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-midnightblue">
            <!-- form start -->
            <form action="{!!  url('admin/organisateur')!!}" method="POST" class="validate-form form-horizontal" data-validate="parsley" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="panel-body event-info">
                    <h4>Ajouter un organisateur</h4>
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
                        <label for="file" class="col-sm-3 control-label">Logo</label>
                        <div class="col-sm-5">
                            <div class="list-group">
                                <div class="list-group-item">
                                    {!! Form::file('file', ['required' => 'required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong>Centre pour les colloque</strong></label>
                        <div class="col-sm-8">
                            <label class="radio-inline"><input type="radio" name="centre" value="1"> Oui</label>
                            <label class="radio-inline"><input type="radio" name="centre" value="0" checked> Non</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">TVA</label>
                        <div class="col-sm-7">
                            <input type="text" name="tva" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-7">
                            {!! Form::textarea('description', null, ['class' => 'form-control  redactorSimple', 'cols' => '50' , 'rows' => '4']) !!}
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