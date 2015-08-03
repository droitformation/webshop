@extends('layouts.colloque')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Colloques</h2>
            <p>&nbsp;</p>
            <?php
                $item = $colloques->first();
            echo '<pre>';
            print_r($item);
            echo '</pre>';
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="grid">
                <div class="grid-sizer"></div>

                @include('colloques.partials.event')

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>

            </div>


        </div>
    </div>


@stop