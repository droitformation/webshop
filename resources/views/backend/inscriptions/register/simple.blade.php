<!-- Inscription simple -->

    <register-simple
            form="simple"
            user_id="{{ $user->id }}"
            _token="{{ csrf_token() }}"
            :colloque="{{ $colloque }}"
            :prices="{{ $colloque->price_display }}"
            :pricelinks="{{ $colloque->price_link_display }}"></register-simple>

        <!-- Occurrences if any -->
{{--
        @if(!$colloque->occurrences->isEmpty())
            <h4>Merci de préciser</h4>
            @include('backend.inscriptions.partials.occurrences', ['select' => 'occurrences[0]'])
        @endif

        @if(!$colloque->prices->isEmpty() || !$colloque->price_link->isEmpty())
            @include('backend.inscriptions.partials.prices',['select' => 'price_id', 'form' => 'simple'])
        @endif

        @include('backend.inscriptions.partials.options', ['select' => 'groupes', 'colloque' => $colloque, 'index' => 0])

        <div class="options-liste-box"></div>

        <input name="user_id" value="{{ $user->id }}" type="hidden">
        <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
        <input name="type" value="{{ $type }}" type="hidden">

        <br><button id="makeInscription" class="btn btn-danger pull-right" type="submit">Inscrire</button>
--}}

    <!-- END Inscriptions -->
