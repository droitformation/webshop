@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-right" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/order/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Commandes</h4>
                </div>
                <div class="panel-body">
                    <table class="table" id="">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>N°</th>
                                <th>Prix</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$orders->isEmpty())
                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{ url('admin/order/'.$order->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ $order->price_cents }}</td>
                                    <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@stop