@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Colloques</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/colloque/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php

            $actives = $colloques->filter(function ($colloque) {
                return $colloque->end_at > date('Y-m-d');
            });

            $active_chunks = $actives->chunk(4);

            $archives = $colloques->filter(function ($colloque) {
                return $colloque->end_at <= date('Y-m-d');
            });

            $years = $archives->groupBy(function ($archive, $key) {
                return $archive->start_at->year;
            });

            $annees = array_keys($years->toArray());

        ?>

        @include('backend.colloques.partials.colloque', ['colloques' => $active_chunks, 'color' => 'primary'])

        <hr/>

        @foreach($annees as $annee_news)
            <a class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#collapseArchives_{{ $annee_news }}">Archives {{ $annee_news }}</a>
        @endforeach

        @foreach($years as $annee => $year)
            <div class="collapse collapseArchive margeUpDown" id="collapseArchives_{{ $annee }}">
                <br/><h4><strong>Archive {{ $annee }}</strong></h4>

                <?php $active_chunks = $year->chunk(4); ?>
                @include('backend.colloques.partials.colloque', ['colloques' => $active_chunks, 'color' => 'warning'])

            </div>
        @endforeach

    </div>
</div>

@stop