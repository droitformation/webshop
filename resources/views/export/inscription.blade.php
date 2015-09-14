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

<table><!-- Start inscriptions -->
    <thead>
    <tr>
        <th>Civilité<th>
        <th>Prénom<th>
        <th>Nom	<th>
        <th>Email<th>
        <th>Profession<th>
        <th>Entreprise<th>
        <th>Téléphone<th>
        <th>Addresse<th>
        <th>NPA	<th>
        <th>Ville<th>
        <th>Canton<th>
        <th>Date d'inscription<th>
        <th>Numéro de facture<th>
        <th>Options<th>
    </tr>
    </thead>
    <tbody>

        @if(!empty($inscriptions))
            @foreach($inscriptions as $inscription)
                <tr>
                    <td><strong>{{ $inscription->adresse_facturation->civilite_title or '' }}</strong></td>
                    <td><?php echo ($inscription->group_id > 0 ? $inscription->participant->name : $inscription->adresse_facturation->name); ?></td>
                    <td>{{ $inscription->adresse_facturation->email }}</td>
                    <td>{{ $inscription->adresse_facturation->company or '' }}</td>
                    <td>{{ $inscription->adresse_facturation->telephone or '' }}</td>
                    <td>{{ $inscription->adresse_facturation->adresse }}</td>
                    <td>{{ $inscription->adresse_facturation->complement or '' }}</td>
                    <td>{{ $inscription->adresse_facturation->cp or '' }}</td>
                    <td>{{ $inscription->adresse_facturation->npa }} {{ $inscription->adresse_facturation->ville }}</td>
                    <td>{{ $inscription->adresse_facturation->canton or '' }}</td>
                    <td><strong>{{ $inscription->created_at->format('d/m/Y') }}</strong></td>
                    <td><strong>{{ $inscription->inscription_no }}</strong></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

