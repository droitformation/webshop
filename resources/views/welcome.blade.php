@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Shop</h2>
    </div>
</div>

<!-- Page Features -->
<div class="row text-center">

    <?php 
    echo '<pre>';
    print_r($products);
    echo '</pre>';
    ?>
    @for ($i = 0; $i < 8; $i++)

        <div class="col-md-3 col-sm-6 hero-feature">
            <div class="thumbnail">
                <img src="http://placehold.it/250x270" alt="">
                <div class="caption">
                    <h4>Feature Label</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    <p><a href="#" class="btn btn-sm btn-primary">Buy Now!</a> <a href="#" class="btn btn-sm btn-default">More Info</a></p>
                </div>
            </div>
        </div>

    @endfor

</div>
<!-- /.row -->

@stop