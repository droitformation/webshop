@extends('team.layouts.master')
@section('content')

    <div class="row">
    <div class="col-md-4">
        <h3>Archives colloques année: {{ $current }}</h3>
        <p><a href="{{ url('team/colloque') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
    </div>
    <div class="col-md-8 text-right">
        <div class="btn-group">
            @foreach($years as $year)
                <a class="btn btn-primary btn-sm {{ $current == $year ? 'active' : '' }}" href="{{ url('team/colloque/archive/'.$year) }}">Archives {{ $year }}</a>
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