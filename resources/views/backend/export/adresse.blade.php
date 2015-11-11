<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>

    @if(!empty($adresses))
        <table>
            <tr>
                <th>Civilité</th>
                <th>Prénom et nom</th>
                <th>Email</th>
                <th>Profession</th>
                <th>Entreprise</th>
                <th>Téléphone</th>
                <th>Mobile</th>
                <th>Addresse</th>
                <th>Complément</th>
                <th>CP</th>
                <th>NPA	</th>
                <th>Ville</th>
                <th>Canton</th>
                <th>Pays</th>
            </tr>

            @foreach($adresses as $adresse)
                <tr>
                    @foreach($columns as $column)
                       <td style="text-align: left;">{{ (isset($adresse->$column) ? trim($adresse->$column) : '') }}</td>
                    @endforeach
                </tr>
            @endforeach

        </table>
    @endif

</body>
</html>