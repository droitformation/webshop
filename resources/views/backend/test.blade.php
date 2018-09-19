@extends('backend.layouts.master')
@section('content')

   <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body" id="appComponent">
                    <image-uploader :wrapper="false" :id="12" inputname="file"></image-uploader>
                </div>
            </div>

        </div>
    </div>

@stop