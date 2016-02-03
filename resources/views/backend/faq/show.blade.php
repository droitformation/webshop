@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/faq') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ($faq)

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/faq/'.$categorie->id) }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter {{ $categorie->titre }}</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Rang</label>
                        <div class="col-sm-2">
                            {!! Form::text('rang', $categorie->rang , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Afficher sur les pages du site</label>
                        <div class="col-sm-4">
                            @if(!$sites->isEmpty())
                                <select class="form-control" name="site_id">
                                    @foreach($sites as $site)
                                        <option {{ $categorie->site_id == $site->id ? 'selected' : '' }} value="{{ $site->id }}">{{ $site->title }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-6">
                            {!! Form::text('title', $categorie->title , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    {!! Form::hidden('id', $faq->id ) !!}
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