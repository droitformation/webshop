@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body" id="appComponent">

                    <form class="form-horizontal" action="{{ url('admin/statistique') }}" method="post">{!! csrf_field() !!}
                        <statistique-filter :search="{{ json_encode($search) }}"></statistique-filter>
                    </form>

                    @if($results)
                        <?php
                        echo '<pre>';
                        print_r($results);
                        echo '</pre>';
                        ?>
                    @endif
                </div>
            </div>

        </div>
    </div>

@stop