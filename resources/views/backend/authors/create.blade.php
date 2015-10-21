@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/author') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/author') }}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>Ajouter un auteur</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Prénom</label>
                    <div class="col-sm-3">
                        {!! Form::text('first_name', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Nom</label>
                    <div class="col-sm-3">
                        {!! Form::text('last_name', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-3">
                        <div class="list-group">
                            <div class="list-group-item">
                                {!! Form::file('photo') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Occupation</label>
                    <div class="col-sm-7">
                        {!! Form::text('occupation', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Biographie</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('bio', null , array('class' => 'form-control redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer">
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