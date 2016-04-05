@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(isset($user->subscriptions) && !$user->subscriptions->isEmpty())
            <h4>Inscrit aux newsletters</h4>
        <ul class="list-group"></ul>
            @foreach($user->subscriptions as $subscription)
                <li class="list-group-item">{{ $subscription->newsletter->titre }}</li>
            @endforeach
        @endif
    </div>
@endsection
