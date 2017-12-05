@extends('access.layouts.master')
@section('content')

    <h3>Adresses</h3>
    <div class="panel panel-midnightblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
                    <h3>Vos listes</h3>
                    @foreach(\Auth::user()->access as $specialisation)
                        <p><strong>{{ $specialisation->title }}</strong></p>
                    @endforeach
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ url('access/adresse/create') }}" class="btn btn-success" id="addAdresse"><i class="fa fa-plus"></i> &nbsp;Ajouter une adresse</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            @if(isset($adresses) && !$adresses->isEmpty())
                <div class="panel panel-midnightblue">
                    <div class="panel-body">
                        <table class="table" id="specialisations_table" style="margin-bottom: 0px;" >
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-2">Nom</th>
                                <th class="col-sm-2">Prénom</th>
                                <th class="col-sm-2">Email</th>
                                <th class="col-sm-2">Entreprise</th>
                                <th class="col-sm-2">Ville</th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                            @foreach($adresses as $adresse)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('access/adresse/'.$adresse->id) }}">&Eacute;diter</a></td>
                                    <td><strong>{{ $adresse->last_name }}</strong></td>
                                    <td><strong>{{ $adresse->first_name }}</strong></td>
                                    <td>{{ $adresse->email }}</td>
                                    <td>{{ $adresse->company }}</td>
                                    <td>{{ $adresse->ville }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                   {{--     @if($adresses instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{$adresses->links()}}
                        @endif--}}

                    </div>
                </div>
            @endif

        </div>
    </div>

@stop