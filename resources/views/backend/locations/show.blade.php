@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/location') !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ( !empty($location) )

    <div class="col-md-6">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!! url('admin/location/'.$location->id) !!}" method="POST" class="validate-form form-horizontal">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter {!! $location->name !!}</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{ $location->name }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Lien externe</label>
                        <div class="col-sm-9">
                            <input type="text" name="url" value="{{ $location->url }}" class="form-control">
                        </div>
                    </div>

                    @if(!empty($location->map ))
                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Fichier</label>
                            <div class="col-sm-3">
                                <div class="list-group">
                                    <div class="list-group-item text-center">
                                        <a href="#"><img height="120" src="{!! asset('files/colloques/cartes/'.$location->map) !!}" alt="{{ $location->name }}" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="file" class="col-sm-3 control-label">Changer la carte</label>
                        <div class="col-sm-7">
                            <div class="list-group">
                                <div class="list-group-item">
                                    {!! Form::file('file') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Adresse</label>
                        <div class="col-sm-6">
                            {!! Form::textarea('adresse', $location->adresse , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        {!! Form::hidden('id', $location->id )!!}
                    </div>
                    <div class="col-sm-9">
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