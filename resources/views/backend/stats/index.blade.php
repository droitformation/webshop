@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <div id="appComponent">
                        <form class="form-horizontal" action="{{ url('admin/statistique') }}" method="post">{!! csrf_field() !!}
                            <statistique-filter :search="{{ json_encode($search) }}"></statistique-filter>
                        </form>
                    </div>

                    @if($results)
                        <?php

                        $data = $results->mapWithKeys(function ($item, $key) {
                            return [$key => (int) $item['results']];
                        });

                        echo '<pre>';
                        print_r($results);
                        echo '</pre>';
                        ?>
                    @endif


                    {{--    @include('backend.stats.chart') --}}


                </div>
            </div>

        </div>
    </div>

@stop