@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Archives colloques année: {{ $current }}</h3>
        <p><a href="{{ url('admin/colloque') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url('admin/colloque/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter un colloque</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <div class="btn-group btn-archives-colloques">
            @foreach($years as $year)
                <a class="btn btn-primary btn-sm {{ $current == $year ? 'active' : '' }}" href="{{ url('admin/colloque/archive/'.$year) }}">Archives {{ $year }}</a>
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