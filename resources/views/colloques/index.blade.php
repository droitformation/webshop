@extends('layouts.colloque')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Colloques</h2>
            <p>&nbsp;</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="grid" id="colloques">
                <div class="grid-sizer"></div>

                    @if($colloques)
                        @foreach($colloques as $colloque)
                            @include('colloques.partials.event')
                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>


@stop