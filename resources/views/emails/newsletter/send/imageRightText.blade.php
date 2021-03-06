
<table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset">
    <tr bgcolor="ffffff"><td colspan="3" height="35"></td></tr>
    <tr align="center" class="resetMarge">
        <td class="resetMarge">

            @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                @slot('picto')
                    <a target="_blank" href="{{ isset($bloc->lien) && !empty($bloc->lien) ? $bloc->link_or_url : url('/') }}">
                        <img style="width: 130px; max-height: 220px;" alt="{{ $bloc->titre ?? '' }}" src="{{ secure_asset(config('newsletter.path.upload').$bloc->image) }}" />
                    </a>
                @endslot

                @slot('content')
                    <h2 style="font-family: sans-serif;">{{ $bloc->titre }}</h2>
                    <div>{!! $bloc->contenu !!}</div>
                @endslot
            @endcomponent

        </td>
    </tr>
    <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr>
</table>
