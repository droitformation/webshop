@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/menus/'.$menu->site_id) }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ($menu)

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/menu/'.$menu->id) }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>&Eacute;diter {{ $menu->titre }}</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="type" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-5">
                        {!! Form::text('title', $menu->title, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="position" class="col-sm-3 control-label">Position</label>
                    <div class="col-sm-5">
                        {!! Form::select('position', $positions, $menu->position , ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Appartient au site</label>
                    <div class="col-sm-5">
                        @if(!$sites->isEmpty())
                            <select class="form-control" name="site_id">
                                @foreach($sites as $site)
                                    <option {{ $menu->site_id == $site->id  ? 'selected' : '' }} value="{{ $site->id  }}">{{ $site->nom }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                <div class="col-sm-3">{!! Form::hidden('id', $menu->id ) !!}</div>
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