
<table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset">
    <tr bgcolor="ffffff"><td colspan="3" height="35"></td></tr>
    <tr align="center" class="resetMarge">
        <td class="resetMarge">

            @component('emails.newsletter.send.partials.widebloc', ['width' => '560'])
                <h2 style="font-family: sans-serif;">{{ $bloc->titre }}</h2>
                <div>{!! $bloc->contenu !!}</div>
            @endcomponent

        </td>
    </tr>
    <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr>
</table>
