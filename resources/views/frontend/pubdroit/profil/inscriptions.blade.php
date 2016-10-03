@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">
        <h4>Inscriptions</h4>
        @if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())
            <div class="profil-info">
                <?php $grouped = $user->inscriptions->sortByDesc('status')->groupBy('status'); ?>

                @foreach($grouped as $inscriptions)
                    <h4 class="bg-{{ $inscriptions->first()->status_name['color'] }}">{{ $inscriptions->first()->status_name['status'] }}</h4>
                    <div class="list-group">
                        @foreach($inscriptions as $inscription)
                            <?php $inscription->load('colloque'); ?>
                            <a href="{{ url('pubdroit/profil/inscription/'.$inscription->id) }}" class="list-group-item">
                                <h5>
                                    {{ $inscription->colloque->titre }}
                                    <div class="pull-right"><span class="glyphicon glyphicon-send"></span> &nbsp;{{ $inscription->created_at->format('d/m/Y') }}</div>
                                </h5>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
