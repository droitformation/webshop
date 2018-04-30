@if(isset($bloc->groupe) && !$bloc->groupe->arrets->isEmpty())

    <!-- Categorie title -->
    @include('emails.newsletter.send.partials.categorie', ['bloc' => $bloc])
    <!-- Categorie title -->

    @foreach($bloc->groupe->arrets as $arret)
        @if(isset($arret))

            @include('emails.newsletter.send.arret', ['arret' => $arret, 'bloc' => $bloc])

        @endif
    @endforeach
@endif