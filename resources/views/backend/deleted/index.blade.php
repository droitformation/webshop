@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Recherche Adresse/Compte</h3>
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
                    <p class="text-success"><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;Adresse active</p>
                    <p class="text-warning"><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;Adresse supprimée</p>

                    @if(!$adresses->isEmpty())
                        <form target="_blank" action="{{ url('admin/deletedadresses/compare') }}" method="post">{!! csrf_field() !!}
                            <table class="table" style="margin-bottom: 0px;" >
                                <thead>
                                <tr>
                                    <th class="col-sm-1">Séléctionner</th>
                                    <th class="col-sm-1">Appartient</th>
                                    <th class="col-sm-2">Nom</th>
                                    <th class="col-sm-1">Email</th>
                                    <th class="col-sm-3">Adresse</th>
                                    <th class="col-sm-3">Possède</th>
                                    <th class="col-sm-1">Action</th>
                                </tr>
                                </thead>
                                <tbody class="selects" id="searchTable">

                                    @foreach($adresses as $group_by => $grouped)
                                        @if($group != 'user_id')
                                            <tr>
                                                <td class="bg-warning" colspan="7">
                                                    <strong>{{ $grouped->first()->$group }}</strong>
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach($grouped as $item)

                                            <?php
                                                if($item instanceof \App\Droit\User\Entities\User){
                                                    $user = $item;
                                                    $adresse = null;
                                                }
                                                elseif($item->user_id > 0){
                                                    $user = $item->trashed_user;
                                                    $adresse = $item;
                                                }
                                                else{
                                                    $user    =  null;
                                                    $adresse = $item;
                                                }
                                            ?>
                                            <?php $partial = ($user ? 'user' : 'adresse'); ?>

                                            @include('backend.deleted.partials.'.$partial.'-row', ['adresse' => $adresse, 'user' => $user])

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