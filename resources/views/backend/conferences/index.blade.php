@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <form action="{{ url('admin/dejeuner') }}" method="POST" class="form">{!! csrf_field() !!}
            <h4><i class="fa fa-home"></i> &nbsp;Déjeuners académiques</h4>
            <div class="panel panel-primary">
                <div class="panel-body">

                    <div class="form-group">
                        <label><strong>Titre</strong></label>
                        <input type="text" class="form-control" name="conference[title]" value="{!! Registry::get('conference.title')!!}">
                    </div>
                    <div class="form-group">
                        <label><strong>Date</strong></label>
                        <input type="text" class="form-control datePicker" name="conference[date]" value="{!! Registry::get('conference.date')!!}">
                    </div>

                    <div class="form-group">
                        <label><strong>Places</strong></label>
                        <input style="width: 120px;" type="text" class="form-control" name="conference[places]" value="{!! Registry::get('conference.places')!!}">
                    </div>

                    <div class="form-group">
                        <label><strong>Commentaire</strong></label>
                        <textarea name="conference[comment]" class="form-control redactorSimple">{!! Registry::get('conference.comment') !!}</textarea>
                    </div>

                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-info btn-sm pull-right">Mettre à jour</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <h4><i class="fa fa-users"></i> &nbsp;Inscriptions</h4>
        @if(!empty(Registry::get('academiques')))

            <div class="panel panel-primary">
                <div class="panel-body">
                    <h4 style="margin-bottom: 20px;">Personnes inscrites</h4>
                    @foreach(Registry::get('academiques') as $index => $personne)

                        <div class="row">
                            <div class="col-md-10">
                                <p><strong><i class="fa fa-caret-right"></i> &nbsp; {{ $personne['first_name'] }} {{ $personne['last_name'] }}</strong></p>
                            </div>
                            <div class="col-md-2 text-right">
                                <form action="{{ url('dejeuner') }}" method="POST" class="form-horizontal">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <input type="hidden" name="index" value="{{ $index }}">
                                    <button data-what="Supprimer" data-action="{{ $personne['first_name'] }} {{ $personne['last_name'] }}" class="btn btn-danger btn-xs deleteAction">x</button>
                                </form>
                            </div>
                        </div>

                    @endforeach
                </div>
                <div class="panel-footer">
                    <form action="{{ url('admin/dejeuner/export') }}" method="POST">{!! csrf_field() !!}
                        <button type="submit" class="btn btn-inverse btn-sm pull-right">Exporter [Excel]</button>
                    </form>
                </div>
            </div>

        @endif

    </div>
</div>

@stop