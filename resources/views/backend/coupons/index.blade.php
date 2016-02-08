@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-right" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/coupon/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Coupons rabais </h4>
                </div>
                <div class="panel-body">

                    <table class="table" id="generic">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Titre</th>
                                <th>Valeur</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$coupons->isEmpty())
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td><a href="{{ url('admin/coupon/'.$coupon->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $coupon->title }}</td>
                                    <td>{{ !empty($coupon->value) ? $coupon->value.' %' : 'gratuit' }} </td>
                                    <td>{{ $coupon->expire_at->formatLocalized('%d %B %Y') }}</td>
                                    <td class="text-right">
                                        @if($coupon->orders->isEmpty())
                                            <form action="{{ url('admin/coupon/'.$coupon->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-what="Supprimer" data-action="{{ $coupon->title }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                            </form>
                                        @else
                                            <span class="text-danger">Coupon utilisé</span>
                                        @endif
                                    </td>
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