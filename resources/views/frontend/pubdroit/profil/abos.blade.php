@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(!$abos->isEmpty())
            <h4>Abonnement aux ouvrages</h4>
            <div class="profil-info">
                @foreach($abos as $abo)
                    <h3><a href="{{ url('pubdroit/product/'.$abo->abo->current_product->id) }}">Abonnement {{ $abo->abo->name }}</a></h3>
                    <p><strong>Dernière édition:</strong> {{ $abo->abo->current_product->title }} </p>
                @endforeach
            </div>
        @endif
    </div>
@endsection
