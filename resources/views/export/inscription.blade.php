<?php
echo '<pre>';
print_r($inscriptions);
echo '</pre>';
?>

<table>
    <tr>
        <td height="80px">
            <h3>{{ $colloque->titre }}</h3>
            <h4>{{ $colloque->soustitre }}</h4>
        </td>
        <td>
            <h4><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->organisateur }}</a></h4>
            <p>{{ $colloque->event_date }}</p>
        </td>
    </tr>
    <tr><td colspan="2"></td></tr>
</table>

@if(!empty($inscriptionihljs))
    @foreach($inscriptijlons as $idoption => $options)



        @if($type == 'choix')
            @foreach($options as $idgroupe => $option)

                <table><tr><td>{{ $allgroupes[$idgroupe] }}</td></tr></table>
                <table>
                    <tr>
                        <th>Civilité</th>
                        <th>Prénom et nom</th>
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Profession</th>
                        <th>Entreprise</th>
                        <th>Téléphone</th>
                        <th>Addresse</th>
                        <th>Compl.</th>
                        <th>CP</th>
                        <th>NPA	</th>
                        <th>Ville</th>
                        <th>Canton</th>
                        <th>Pays</th>
                        <th>Date</th>
                        <th>Numéro</th>
                        <th>Options</th>
                    </tr>
                </table>
                @include('export.table', ['inscriptions' => $option])
            @endforeach
        @endif

    @endforeach
@endif