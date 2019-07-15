@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-2">
        <h3>Colloques</h3>
    </div>
    <div class="col-md-10 text-right">
        <div class="btn-group">
            <a href="{{ url('admin/colloque/create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> &nbsp;Ajouter un colloque</a>
            @foreach($years as $year)
                <a class="btn btn-primary btn-sm" href="{{ url('admin/colloque/archive/'.$year) }}">Archives {{ $year }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php $active_chunks = $colloques->chunk(4); ?>

        @include('backend.colloques.partials.colloque', ['colloques' => $active_chunks, 'color' => 'primary'])

    </div>
</div>

@stop