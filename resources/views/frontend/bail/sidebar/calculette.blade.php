<div class="widget clear">
    <h3 class="title">Calculateur</h3>

    <p>Calculez les hausses et baisses de loyer en un clic</p>

    <form action="{{ url('bail/calcul') }}" id="calculette">
        {!! csrf_field() !!}
        <label>Votre canton</label>
        <select class="form-control" name="canton" id="input-canton" required>
            <option value="">Choix</option>
            @foreach($faqcantons as $canton_id => $canton)
                <option value="{{ $canton_id }}">{{ $canton }}</option>
            @endforeach
        </select>

        <label>Votre loyer actuel (sans les charges)</label>
        <input type="text" class="form-control" name="loyer" id="input-loyer" required>

        <label>Date de la conclusion du bail ou date de réception de la notification de loyer (hausse/baisse)</label>
        <input type="text" class="form-control datepicker" name="date" id="input-datepicker" required>
        <br>
        <p><button class="btn btn-danger btn-sm" type="submit">Calculer</button></p>
    </form>

    <div id="calculatorResult"></div>

</div>