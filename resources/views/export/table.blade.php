<table border="1"><!-- Start inscriptions -->
    <thead>
        <tr>
            <th>Civilité</th>
            <th>Prénom et nom</th>
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
            <th>Date</th>
            <th>Numéro</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>

        @foreach($inscriptions as $inscription)
            <tr>
                <td><strong>{{ $inscription->adresse_facturation->civilite_title or '' }}</strong></td>
                <td><?php echo ($inscription->group_id > 0 ? $inscription->participant->name : $inscription->adresse_facturation->name); ?></td>
                <td>{{ $inscription->adresse_facturation->email }}</td>
                <td>{{ $inscription->adresse_facturation->profession->title or '' }}</td>
                <td>{{ $inscription->adresse_facturation->telephone or '' }}</td>
                <td>{{ $inscription->adresse_facturation->company or '' }}</td>
                <td>{{ $inscription->adresse_facturation->adresse }}</td>
                <td>{{ $inscription->adresse_facturation->complement or '' }}</td>
                <td>{{ $inscription->adresse_facturation->cp or '' }}</td>
                <td>{{ $inscription->adresse_facturation->npa }}</td>
                <td> {{ $inscription->adresse_facturation->ville }}</td>
                <td>{{ $inscription->adresse_facturation->canton->title or '' }}</td>
                <td><strong>{{ $inscription->created_at->format('d/m/Y') }}</strong></td>
                <td><strong>{{ $inscription->inscription_no }}</strong></td>
                <td>
                    <?php
                        $filtered = $inscription->options->filter(function ($item) {  return $item->type == 'checkbox';  });
                        $filtered = $filtered->lists('title')->all();
                        echo implode('</br>',$filtered);
                    ?>
                </td>
            </tr>
        @endforeach

    </tbody>
</table>

