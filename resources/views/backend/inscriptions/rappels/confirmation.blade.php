@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/inscription/rappels/'.$colloque->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux rappels</a></p>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">

                    <h3 class="text-info">Confirmer et envoyer les rappels</h3>

                    @if(!$inscriptions->isEmpty())
                        <form action="{{ url('admin/register/rappel/send') }}" method="POST">{!! csrf_field() !!}
                            <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                            <div class="row">
                                <div class="col-md-6"><input id="select_all" type="checkbox"> &nbsp;Tout cocher/décocher</div>
                                <div class="col-md-6 text-right"><button class="btn btn-info" type="submit"><i class="fa fa-bell"></i> &nbsp;Envoyer les rappels</button></div>
                            </div>

                            <div class="checkbox_all">
                                @foreach($inscriptions as $inscription)

                                    <div class="input-wrapper">

                                        <input type="checkbox" class="rappel-input" checked name="inscriptions[]" value="{{ $inscription->id }}" />
                                        <strong>{{ $inscription->inscrit->name }}</strong>

                                        <a target="_blank" href="{{ url('preview/rappel/'.$inscription->id) }}" class="btn btn-default btn-sm pull-right">Voir l'email envoyé</a>

                                        @if(!$inscription->occurrences->isEmpty() && !$inscription->occurrence_done->isEmpty())
                                            <ul>
                                                @foreach($inscription->occurrences as $occurrence)
                                                    <li>{{ $occurrence->starting_at->formatLocalized('%d %B %Y') }} | {{ $occurrence->title }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p><strong>Date:</strong> <?php echo $colloque->event_date; ?> |  <?php echo $colloque->location->name.', '.strip_tags($colloque->location->adresse); ?></p>
                                        @endif

                                    </div>

                                @endforeach
                            </div><br/>
                            <p class="text-right"><button class="btn btn-info pull-right" type="submit"><i class="fa fa-bell"></i> &nbsp;Envoyer les rappels</button></p>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

@stop