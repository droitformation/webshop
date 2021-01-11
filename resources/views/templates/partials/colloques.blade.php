<tr><td><h4 style="font-size: 16px;margin-bottom: 10px;">Vos participations</h4></td></tr>
<tr valign="top">
    <td valign="top">
        <table class="content-table" valign="top">
            @foreach($colloques->chunk(2) as $row)
                <tr>
                    @foreach($row as $colloque)
                        <td width="50%">
                            <h3 style="font-size: 14px; font-weight: bold;">{{ $colloque->titre }}</h3>
                            <h4 style="margin-bottom: 5px;font-weight: normal;">{{ $colloque->soustitre }}</h4>

                            <table class="content-table" valign="top">
                                <tr><td class="organisateur colloque-infos"><strong>Organisé par:</strong></td><td>{{ $colloque->organisateur }}</td></tr>
                                <tr><td class="titre-info colloque-infos"><strong>Date:</strong></td><td>{{ $colloque->event_date }}</td></tr>
                                <tr><td class="colloque-infos"><strong>Lieu:</strong></td><td>{{ $colloque->location->name.', '.strip_tags($colloque->location->adresse) }}</td></tr>
                            </table>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </td>
</tr>

