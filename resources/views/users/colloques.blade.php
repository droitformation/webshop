@extends('layouts.user')
@section('content')

<div class="col-md-9 content">

    <div class="panel panel-default">
        <div class="panel-heading">Vos inscriptions</div>
        <div class="panel-body">

            @if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

                <?php $grouped = $user->inscriptions->groupBy('status'); ?>

                @foreach($grouped as $inscriptions)
                <div class="list-group">
                    <div class="list-group-item-title bg-{{ $inscriptions->first()->status_name['color'] }}">{{ $inscriptions->first()->status_name['status'] }}</div>
                    @foreach($inscriptions as $inscription)
                    <?php $inscription->load('colloque'); ?>
                    <a href="{{ url('profil/inscription/'.$inscription->id) }}" class="list-group-item">
                        <h5>{{ $inscription->colloque->titre }}</h5>
                        <p><span class="glyphicon glyphicon-send" aria-hidden="true"></span> &nbsp;{{ $inscription->created_at->format('d/m/Y') }}</p>
                    </a>
                    @endforeach
                </div>
                @endforeach

            @endif

        </div><!-- end panel body -->
    </div><!-- end panel -->

</div>

@endsection
