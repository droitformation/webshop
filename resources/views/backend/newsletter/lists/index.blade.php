@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <h4>Listes hors campagne</h4>
                    @if(isset($lists) && !$lists->isEmpty())
                    <ul class="list-group">
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
