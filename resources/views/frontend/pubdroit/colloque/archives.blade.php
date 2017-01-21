@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

            <div class="heading-bar">
                <h2>Archives colloques</h2>
                <span class="h-line"></span>
            </div>

            <div class="year-holder">
                @if(!$colloques->isEmpty())

                    <?php
                        $years = $colloques->groupBy(function ($colloque, $key) {
                            return $colloque->start_at->format('Y');
                        });
                    ?>

                    @foreach($years as $annee => $year)
                        <h2>{{ $annee }}</h2>
                        <div class="side-inner-holder">
                            <?php $chunks = $year->chunk(2); ?>
                            @foreach($chunks as $chunk)
                                <section class="row">
                                    @foreach($chunk as $colloque)
                                        @include('frontend.pubdroit.colloque.partials.event', ['colloque' => $colloque])
                                    @endforeach
                                </section>
                            @endforeach
                        </div>
                    @endforeach
                @endif

            </div>

        </div>
    </section>

@stop
