@extends('layouts.user')
@section('content')

<div class="col-md-9 content">

    <div class="panel panel-default">
        <div class="panel-heading">Vos inscriptions</div>
        <div class="panel-body">

            @if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())
                @foreach($user->inscriptions as $inscription)
                    <?php $inscription->load('colloque'); ?>
                    <a href="{{ url('profil/inscription/'.$inscription->id) }}">{{ $inscription->colloque->titre }}</a>
                @endforeach
            @endif

        </div><!-- end panel body -->
    </div><!-- end panel -->

</div>

@endsection
