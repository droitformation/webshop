@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Adresses comparer</h3>
    </div>
    <div class="col-md-6 text-right"></div>
</div>

<div class="row">
    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-body">

                @if(!$adresses->isEmpty())
                    @foreach($adresses as $adresse)
                        <div class="well well-sm">
                            <p>{{ $adresse->name }}</p>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

    </div>
    <div class="col-md-6">

        <div class="panel panel-success">
            <div class="panel-body">

            </div>
        </div>

    </div>
</div>

@stop