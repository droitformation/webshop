<!-- Bloc -->
<table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset">
    <tr bgcolor="ffffff"><td colspan="3" height="35"></td></tr><!-- space -->
    <tr align="center" class="resetMarge">
        <td class="resetMarge">

            @component('emails.newsletter.send.partials.widebloc', ['width' => '560'])
                <p class="centerText">
                    <a target="_blank" href="{{ isset($bloc->lien) && !empty($bloc->lien) ? $bloc->link_or_url : url('/') }}">
                        <img style="width: 560px;" alt="{{ $bloc->titre or '' }}" src="{{ secure_asset(config('newsletter.path.upload').$bloc->image) }}" />
                    </a>
                </p>
                {!!  $bloc->titre ? '<h2 class="centerText">'. $bloc->titre.'<h2>' : '' !!}
            @endcomponent

        </td>
    </tr>
    <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr><!-- space -->
</table>
<!-- End bloc -->

