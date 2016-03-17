@extends('backend.newsletter.lists.index')
@section('list')
    @parent

    <div class="panel panel-primary">
        <div class="panel-body">
            @if(isset($list) && !$list->emails->isEmpty())

                <form action="{{ url('admin/liste/send') }}" method="POST">{!! csrf_field() !!}
                    <div class="form-group" style="margin-bottom: 20px;">
                        <input name="campagne_id" value="1314" type="hidden">
                        <input name="list_id" value="{{ $list->id }}" type="hidden">
                        <button type="submit" class="btn btn-success btn-sm">Envoyer</button>
                    </div>
                </form>

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