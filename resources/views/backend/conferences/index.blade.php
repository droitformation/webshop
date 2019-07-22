@extends('backend.layouts.master')
@section('content')

    <form action="{{ url('admin/conference') }}" method="POST" class="form">{!! csrf_field() !!}

        <div class="row">
            <div class="col-md-6">
                <h4><i class="fa fa-home"></i> &nbsp;Inscriptions aux Déjeuners académiques</h4>
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
            </div>

            <div class="col-md-6">


            </div>
        </div>

    </form>

@stop