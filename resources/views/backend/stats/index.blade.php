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
                      /* */ echo '<pre>';
                        print_r($search);
                        echo '</pre>';
                        ?>

                            @if(isset($datapoints['labels']) && $datapoints['datasets'])

                        {{--    @include(whatTable($search))--}}

                            @include('backend.stats.chart',['datapoints ' => $datapoints])
                        @endif

                    @endif

                </div>
            </div>

        </div>
    </div>

@stop