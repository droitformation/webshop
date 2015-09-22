@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Inscriptions</h2>
            <p>&nbsp;</p>
        </div>
    </div>

    <table class="table table-list">
        <thead>
            <tr>
                <th class="numeroInscriptionSorter">N°</th>
                <th>Date</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Entreprise</th>
                <th class="{sorter: false}">ID</th>
            </tr>
        </thead>
        <tbody>
        @if(!$inscriptions->isEmpty())
            @foreach($inscriptions as $inscription)
                <tr>
                    <td><span class="label label-primary">{{ $inscription->inscription_no }}</span></td>
                    <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                    <td>{{ $inscription->user->first_name }}</td>
                    <td>{{ $inscription->user->last_name }}</td>
                    <td>{{ $inscription->user->email }}</td>
                    <td>{{ $inscription->user->adresse_livraison->company or '' }}</td>
                    <td><a class="btn btn-info btn-sm" href="{{ url('inscription/'.$inscription->id) }}">éditer</a></td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@stop