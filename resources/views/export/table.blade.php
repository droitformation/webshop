<table>
    <tr>
        <th>Civilité</th>
        <th>Prénom et nom</th>
        <th>Participant</th>
        <th>Email</th>
        <th>Profession</th>
        <th>Entreprise</th>
        <th>Téléphone</th>
        <th>Mobile</th>
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
    @foreach($rows as $option)
        <tr><?php echo implode('',$option); ?></tr>
    @endforeach
</table>



