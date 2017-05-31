@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(!$subscriptions->isEmpty())
            <h4>Inscriptions aux newsletters</h4>
            <div class="profil-info">
                @foreach($subscriptions as $email => $sub)
                    <p><strong>{{ $email }}</strong></p>
                    <ul class="list-group">
                        @foreach($sub as $subscription)
                            <li class="list-group-item">
                                {{ $subscription->titre }}
                                <?php $slug = $subscription->site_id && $subscription->site->slug != 'pubdroit' ? $subscription->site->slug : 'pubdroit'; ?>
                                <a target="_blank" class="btn btn-danger btn-xs pull-right" href="{{ url($slug.'/unsubscribe') }}">se désinscrire</a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endif
    </div>
@endsection
