@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-4">
            <h3>Listes hors campagne</h3>
        </div>
        <div class="col-md-8 text-right">
           <p><a href="{{ url('admin/liste') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">
                    @if(isset($lists) && !$lists->isEmpty())
                    <ul class="list-group list-group-import">
                        @foreach($lists as $list)
                            <li class="list-group-item">
                                <a href="{{ url('admin/liste/'.$list->id) }}">{{ $list->title }}</a><span class="label label-default pull-right">{{ $list->created_at->format('Y-m-d') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Contenu -->
            @yield('list')
            <!-- Fin contenu -->
        </div>
    </div>

@stop
