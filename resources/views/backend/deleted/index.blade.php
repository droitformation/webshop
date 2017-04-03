@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Adresses supprimées</h3>
    </div>
    <div class="col-md-6 text-right"></div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">
            <div class="panel-body" id="appComponent">

                <form action="{{ url('admin/deletedadresses') }}" method="post">{!! csrf_field() !!}
                    <div class="row">
                        <filter-adresse selected="{{ $type }}" checked="{{ $group }}" operator="{{ $operator }}" terms="{{ json_encode($terms) }}"></filter-adresse>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-midnightblue">
            <div class="panel-body">

                <div class="table-responsive">
                    @if(!$adresses->isEmpty())
                        <form target="_blank" action="{{ url('admin/deletedadresses/compare') }}" method="post">{!! csrf_field() !!}
                            <table class="table" style="margin-bottom: 0px;" >

                                <thead>
                                <tr>
                                    <th class="col-sm-1">Séléctionner</th>
                                    <th class="col-sm-1">Appartient</th>
                                    <th class="col-sm-2">Nom</th>
                                    <th class="col-sm-2">Email</th>
                                    <th class="col-sm-3">Adresse</th>
                                    <th class="col-sm-3">Possède</th>
                                </tr>
                                </thead>
                                <tbody class="selects" id="searchTable">

                                    @foreach($adresses as $group_by => $grouped)
                                        @if($group != 'user_id')
                                            <tr><td class="bg-warning" colspan="7"><strong>{{ $group }}:</strong> {{ $group_by }}</td></tr>
                                        @endif
                                        @foreach($grouped as $adresse)

                                            <?php $partial = $adresse->user_id > 0 ? 'user' : 'adresse'; ?>

                                            @include('backend.deleted.partials.'.$partial.'-row', ['adresse' => $adresse])
                                        @endforeach
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7">
                                            <button type="submit" class="btn btn-primary btn-sm">Comparer</button>
                                        </th>
                                    </tr>
                                </tfoot>

                            </table>
                        </form>

                        @if($adresses instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{ $adresses->links() }}
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@stop