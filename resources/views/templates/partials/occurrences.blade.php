@if(isset($occurrences) && !$occurrences->isEmpty())
    <tr><td height="3">&nbsp;</td></tr>
    <tr valign="top">
        <td valign="top">
            <h4>Conférences:</h4>
            <ol style="margin-left: 20px; margin-top: 10px;">
                @foreach($occurrences as $occurrence)
                    <li>Le {{ $occurrence->start_at->formatLocalized('%d %B %Y') }}: <br/><strong>{{ $occurrence->title }}</strong></li>
                @endforeach
            </ol>
        </td>
    </tr>
@endif
