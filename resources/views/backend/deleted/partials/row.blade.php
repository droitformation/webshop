<tr>
    <td>

        <div class="control-inline">
            <input class="styled-checkbox" name="adresses[]" id="compare_{{ $adresse->id }}" type="checkbox" value="{{ $adresse->id }}">
            <label for="compare_{{ $adresse->id }}">&nbsp;</label>
        </div>

    </td>
    <td>{!! $adresse->user_id > 0 ? '<span class="label label-info">Compte</span>' : '<span class="label label-success">Adresse simple</span>' !!}</td>
    <td>
        <strong>{{ isset($adresse->civilite) ? $adresse->civilite->title : '' }} {{ $adresse->first_name }} {{ $adresse->last_name }}</strong><br>
        {!! !empty($adresse->company) ? '<p>'.$adresse->company.'</p>' : ''  !!}
    </td>
    <td>{{ $adresse->email }}</td>
    <td>
        {{ $adresse->adresse }}<br>
        {!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
        {!! !empty($adresse->cp) ? $adresse->cp_trim.'<br>' : '' !!}
        {{ $adresse->npa }} {{ $adresse->ville }}<br>
        {{ isset($adresse->pays) ? $adresse->pays->title : '' }}
    </td>
    <td>
        <dl>
            <?php $person = isset($adresse->user) ? $adresse->user : $adresse; ?>

            @if(!$person->orders->isEmpty())
                <dt>Commandes</dt>
                @foreach($person->orders as $order)
                    <dd>{{ $order->order_no }}</dd>
                @endforeach
            @endif

            @if(isset($adresse->user) && !$adresse->user->inscriptions->isEmpty())
                <dt>Inscriptions</dt>
                @foreach($adresse->user->inscriptions as $inscription)
                    <dd>{{ $inscription->inscription_no }}</dd>
                @endforeach
            @endif

            @if(isset($person->abos) && !$person->abos->isEmpty())
                <dt>Abonnements</dt>
                @foreach($person->abos as $abo)
                    <dd>{{ $abo->numero }}</dd>
                @endforeach
            @endif

        </dl>
    </td>
</tr>