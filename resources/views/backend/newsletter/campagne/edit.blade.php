@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-8">

        <div class="options" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/newsletter') }}" class="btn btn-info"><i class="fa fa-chevron-left"></i> &nbsp;Retour aux newsletter</a>
                <a href="{{ url('admin/campagne/'.$campagne->id) }}" class="btn btn-inverse pull-right"> Composer la campagne  &nbsp;<i class="fa fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="panel panel-primary">

            <form action="{{ url('admin/campagne/'.$campagne->id) }}" id="newsletter" data-validate="parsley" method="POST" class="validate-form form-horizontal">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter la campagne</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Sujet</label>
                        <div class="col-sm-6">
                            {!! Form::text('sujet', $campagne->sujet , array('class' => 'form-control') ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Auteurs</label>
                        <div class="col-sm-6">
                            {!! Form::text('auteurs', $campagne->auteurs , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    {!! Form::hidden('id', $campagne->id ) !!}
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary" type="submit">Éditer</button>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>

@stop
