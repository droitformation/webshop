@extends('team.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-4">
            <h3>Colloques</h3>
        </div>
        <div class="col-md-8 text-right">
            <div class="btn-group">
                @foreach($years as $year)
                    <a class="btn btn-primary btn-sm" href="{{ url('team/colloque/archive/'.$year) }}">Archives {{ $year }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php $active_chunks = $colloques->chunk(4); ?>
            @include('team.colloques.partials.colloque', ['colloques' => $active_chunks, 'color' => 'primary'])
        </div>
    </div>

@stop