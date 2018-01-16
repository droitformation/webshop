@if(!empty($adresses))

    <table>
        <tr>
            <th>Civilit&eacute;</th>
            <th>Pr&eacute;nom et nom</th>
            <th>Email</th>
            <th>Profession</th>
            <th>Entreprise</th>
            <th>T&eacute;l&eacute;phone</th>
            <th>Mobile</th>
            <th>Addresse</th>
            <th>Compl&eacute;ment</th>
            <th>CP</th>
            <th>NPA	</th>
            <th>Ville</th>
            <th>Canton</th>
            <th>Pays</th>
        </tr>
        @foreach($adresses as $adresse)
            <tr>
                @foreach($adresse as $column)
                   <td>{!! $column !!}</td>
                @endforeach
            </tr>
        @endforeach
    </table>

@endif
