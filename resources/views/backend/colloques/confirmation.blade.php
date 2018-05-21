@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/colloque/'.$colloque->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour au colloque</a></p>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">

                    <h3 class="text-info">Confirmer et envoyer les liens aux emails</h3>

                    @if(!$emails->isEmpty())
                        @foreach($emails as $email)
                            <p>{{ $email }}</p>
                        @endforeach

                        <form action="{{ url('admin/slide/send') }}" method="POST">{!! csrf_field() !!}
                            <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                            <button class="btn btn-inverse btn-sm">Envoyer</button>
                        </form>
                    @else
                        <p class="text-danger"><i class="fa fa-exclamation-triangle"></i> &nbsp;Aucun email dans cette liste</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

@stop