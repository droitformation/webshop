@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/colloque/'.$colloque->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour au colloque</a></p>

    <div class="row">
        <div class="col-md-12">
            <h3 class="text-info">Confirmer et envoyer les liens aux emails</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">

                    <h3>Colloque:</h3>

                    @if(!$emails->isEmpty())
                        <div class="well well-sm" style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 style="margin-bottom: 0;"><strong>{{ $colloque->titre }}</strong></h3>
                                </div>
                                <div class="col-md-4 text-right">
                                    <form action="{{ url('admin/slide/send') }}" method="POST">{!! csrf_field() !!}
                                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                                        <button class="btn btn-primary">Envoyer les liens</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <h3>Emails:</h3>

                        <ul>
                            @foreach($emails as $email)
                                <li>{{ $email }}</li>
                            @endforeach
                        </ul>

                    @else
                        <p class="text-danger"><i class="fa fa-exclamation-triangle"></i> &nbsp;Aucun email dans cette liste</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

@stop