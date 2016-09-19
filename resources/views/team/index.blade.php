@extends('team.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-3">
            <a class="shortcut-tiles tiles-info" href="{{ url('team/orders') }}">
                <div class="tiles-body">
                    <div class="text-center"><i class="fa fa-shopping-cart"></i></div>
                </div>
                <div class="tiles-footer"><h3>Commmandes</h3></div>
            </a>
        </div>
    </div>

@stop