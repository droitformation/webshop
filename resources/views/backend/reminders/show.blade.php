@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/reminder') !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <?php $config = config('reminder.'.$reminder->type); ?>

            <!-- form start -->
            <form action="{!! url('admin/reminder/'.$reminder->id) !!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-body">
                    <h4>&Eacute;diter {!! $reminder->title !!}</h4>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-7">
                            <input type="text" name="title" value="{{ $reminder->title }}" required class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">

                        <label for="message" class="col-sm-3 control-label">Depuis</label>
                        <div class="col-sm-7">
                            @if(!empty($config))
                                <select class="form-control" name="start">
                                    @foreach($config['dates'] as $date => $human)
                                        <option {{ $reminder->send_at == $date ? 'selected' : '' }} required value="{{ $date }}">{{ $human }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Interval</label>
                        <div class="col-sm-7">
                            <select class="form-control" name="duration">
                                <option value="">Choix</option>
                                <option {{ $reminder->duration == 'week' ? 'selected' : '' }} value="week">1 semaine</option>
                                <option {{ $reminder->duration == 'month' ? 'selected' : '' }} value="month">1 mois</option>
                                <option {{ $reminder->duration == 'trimester' ? 'selected' : '' }} value="trimester">3 mois</option>
                                <option {{ $reminder->duration == 'semester' ? 'selected' : '' }} value="semester">6 mois</option>
                                <option {{ $reminder->duration == 'year' ? 'selected' : '' }} value="year">1 an</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">

                        <label for="message" class="col-sm-3 control-label">Email de rappel pour {{ $config['name'] }}</label>
                        <div class="col-sm-7">
                            @if($items && !$items->isEmpty())
                                <select class="form-control" name="model_id">
                                    @foreach($items as $item)
                                        <option {{ $reminder->model_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title or $item->titre }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                        <div class="col-sm-7">
                            <textarea name="text" class="form-control redactor">{{ $reminder->text }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        <input type="hidden" name="id" value="{{ $reminder->id }}">
                        <input type="hidden" name="model" value="{{ $reminder->model }}">
                        <input type="hidden" name="type" value="{{ $reminder->type }}">
                    </div>
                    <div class="col-sm-9">
                        <button id="editReminder" class="btn btn-primary" type="submit">Envoyer </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>
<!-- end row -->

@stop