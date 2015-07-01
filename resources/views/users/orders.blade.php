@extends('layouts.master')
@section('content')

    <div class="container-fluid main-container">

        @include('users.partials.nav')

        <div class="col-md-9 content">

            <div class="panel panel-default">
                <div class="panel-heading">Commandes</div>
                <div class="panel-body">
                    <?php
                        setlocale(LC_ALL, 'fr_FR.UTF-8');
                        $user->orders->load('products');
                    ?>
                    <?php

/*                  echo '<pre>';
                    print_r($user->orders);
                    echo '</pre>';*/

                    ?>

                    @if(!empty($user->orders))
                        @foreach($user->orders as $orders)

                                <p><strong>{{ $orders->created_at->formatLocalized('%d %B %Y') }}</strong></p>
                                @if(!empty($orders->products))
                                    <table class="table table-bordered">
                                    @foreach($orders->products as $products)
                                        <tr>
                                            <td>{{ $products->title }}</td>
                                            <td class="text-right">{{ $products->price_cents }} CHF</td>
                                        </tr>
                                    @endforeach
                                    </table>
                                @endif

                        @endforeach
                    @endif


                </div><!-- end panel body -->
            </div><!-- end panel -->

        </div>
    </div>

@endsection
