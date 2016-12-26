@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-4">
        <h3>Rappels via email</h3>
    </div>
    <div class="col-md-8">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">

                <?php $items = config('reminder'); ?>

                @if(!empty($items))
                    @foreach($items as $type => $title)
                        <a href="{{ url('admin/reminder/create/'.$type) }}" id="reminder_{{ $type }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter un rappel {{ $title['name'] }}</a>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xs-12">

            <div class="panel panel-primary">
                <div class="panel-body">

                    <h4>A envoyer</h4>

                    @if(!$reminders->isEmpty())
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-3">Titre</th>
                                <th class="col-sm-2">Type</th>
                                <th class="col-sm-2">Date d'envoi</th>
                                <th class="col-sm-2 no-sort"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                                @foreach($reminders as $reminder)
                                    <tr>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/reminder/'.$reminder->id) }}"><i class="fa fa-edit"></i></a></td>
                                        <td><strong>{{ $reminder->title }}</strong></td>
                                        <td>{{ $items[$reminder->type]['name'] }}</td>
                                        <td><strong>{{ $reminder->send_at->formatLocalized('%d %B %Y') }}</strong></td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/reminder/'.$reminder->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button id="deleteReminder_{{ $reminder->id }}" data-what="Supprimer" data-action="{{ $reminder->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    {!! $reminders->links() !!}
                    @else
                        <p>Encore aucun rappel à envoyer</p>
                    @endif
            </div>
        </div>

    </div>
    <div class="col-md-6 col-xs-12">

            @if(!$trashed->isEmpty())

                <div class="panel panel-inverse">
                    <div class="panel-body">

                        <h4>Terminés</h4>

                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-sm-3">Titre</th>
                                <th class="col-sm-2">Type</th>
                                <th class="col-sm-3">Date d'envoi</th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                            @foreach($trashed as $trash)
                                <tr>
                                    <td><strong>{{ $trash->title }}</strong></td>
                                    <td>{{ $items[$trash->type]['name'] }}</td>
                                    <td><strong>{{ $trash->send_at->formatLocalized('%d %B %Y') }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {!! $trashed->links() !!}

                    </div>
                </div>

            @endif

    </div>

</div>


@stop