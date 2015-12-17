@extends('backend.layouts.master')
@section('content')
    
    <div class="row">
        <div class="col-md-12">
            <h3>Factures</h3>
            
            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <table class="table">
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
                        @if(!$factures->isEmpty())
                            @foreach($factures as $facture)
                                <tr>
                                    <td><a href="{{ url('admin/facture/'.$facture->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $facture->title }}</td>
                                    <td>{{ $facture->value }} %</td>
                                    <td>{{ $facture->expire_at->formatLocalized('%d %B %Y') }}</td>
                                    <td class="text-right">
                                        @if($facture->orders->isEmpty())
                                            <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-what="Supprimer" data-action="{{ $facture->title }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                            </form>
                                        @else
                                            <span class="text-danger">Facture utilisé</span>
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