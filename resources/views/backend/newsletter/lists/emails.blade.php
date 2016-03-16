@extends('backend.newsletter.lists.index')
@section('list')
    @parent

    <div class="panel panel-primary">
        <div class="panel-body">
            @if(isset($list) && !$list->emails->isEmpty())
                <h4>{{ $list->title }}</h4>
                <ul class="list-unstyled">
                    @foreach($list->emails as $email)
                        <li>{{ $email->email }}</li>
                    @endforeach
                </ul>
            @else
                <p>Aucun email dans cette liste</p>
            @endif
        </div>
    </div>

@endsection