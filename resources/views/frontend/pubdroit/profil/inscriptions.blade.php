@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

            <?php $grouped = $user->inscriptions->groupBy('status'); ?>

            @foreach($grouped as $inscriptions)
                <h4 class="bg-{{ $inscriptions->first()->status_name['color'] }}">{{ $inscriptions->first()->status_name['status'] }}</h4>
                <div class="list-group">
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
    </div>
@endsection
