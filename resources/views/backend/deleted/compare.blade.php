@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Adresses comparer</h3>
    </div>
    <div class="col-md-6 text-right"></div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class='examples'>
            <div class='parent'>
                <div class='wrapper'>
                    <div id='left-defaults' class='container_dd'>

                        @if(!$adresses->isEmpty())
                            @foreach($adresses as $adresse)
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>{{ $adresse->name }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <div id='right-defaults' class='container_dd'>
                        <div>There's also the possibility of moving elements around in the same container, changing their position</div>
                        <div>This is the default use case. You only need to specify the containers you want to use</div>

                    </div>
                    <div id='right-defaults2' class='container_dd'>
                        <div>There's also the possibility of moving elements around in the same container, changing their position</div>
                        <div>This is the default use case. You only need to specify the containers you want to use</div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">


    </div>
</div>

@stop