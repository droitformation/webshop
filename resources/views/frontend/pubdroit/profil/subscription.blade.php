@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(!$subscriptions->isEmpty())
            <h4>Inscrit aux newsletters</h4>
            <div class="profil-info">
                @foreach($subscriptions as $email)
                    <p><strong>{{ $email->email }}</strong></p>
                    <ul class="list-group">
                        @foreach($email->subscriptions as $subscription)
                            <li class="list-group-item">{{ $subscription->titre }}</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endif
    </div>
@endsection
