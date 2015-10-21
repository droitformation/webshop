@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/author') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ( !empty($author) )

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/author/'.$author->id) }}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>&Eacute;diter {{ $author->name }}</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Prénom</label>
                    <div class="col-sm-3">
                        {!! Form::text('first_name', $author->first_name , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Nom</label>
                    <div class="col-sm-3">
                        {!! Form::text('last_name', $author->last_name , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                @if(!empty($author->photo ))
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Photo</label>
                    <div class="col-sm-3">
                        <div class="list-group">
                            <div class="list-group-item text-center">
                                <a href="#"><img height="120" src="{{ asset('authors/'.$author->photo) }}" alt="{{$author->name}}" /></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Changer la Photo</label>
                    <div class="col-sm-3">
                        <div class="list-group">
                            <div class="list-group-item">
                                {!! Form::file('photo')  !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Occupation</label>
                    <div class="col-sm-7">
                        {!! Form::text('occupation', $author->occupation , array('class' => 'form-control') )  !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Biographie</label>
                    <div class="col-sm-7">
                        {!! Form::textarea('bio', $author->bio , array('class' => 'form-control redactor', 'cols' => '50' , 'rows' => '4' ))  !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                {!! Form::hidden('id', $author->id ) !!}
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
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