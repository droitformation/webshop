<table border="1"><!-- Start inscriptions -->
    @foreach($inscriptions as $inscription)
        <tr>
            <td><strong>{{ $inscription->adresse_facturation->civilite_title or '' }}</strong></td>
            <td><?php echo ($inscription->group_id > 0 ? $inscription->participant->name : $inscription->adresse_facturation->name); ?></td>
            <td>{{ $inscription->adresse_facturation->email }}</td>
            <td>{{ $inscription->adresse_facturation->profession_title }}</td>
            <td>{{ $inscription->adresse_facturation->telephone or '' }}</td>
            <td>{{ $inscription->adresse_facturation->company or '' }}</td>
            <td>{{ $inscription->adresse_facturation->adresse }}</td>
            <td>{{ $inscription->adresse_facturation->complement or '' }}</td>
            <td>{{ $inscription->adresse_facturation->cp or '' }}</td>
            <td>{{ $inscription->adresse_facturation->npa }}</td>
            <td>{{ $inscription->adresse_facturation->ville }}</td>
            <td>{{ $inscription->adresse_facturation->canton_title }}</td>
            <td>{{ $inscription->adresse_facturation->pays_title }}</td>
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
</table>

