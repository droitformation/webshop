<table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset">
    <tr bgcolor="ffffff"><td colspan="3" height="35"></td></tr>
    <tr align="center" class="resetMarge">
        <td class="resetMarge">

            @component('emails.newsletter.send.partials.widebloc', ['width' => '560'])
                <?php $width = \Image::make(public_path(config('newsletter.path.upload').$bloc->image))->width();?>
                <?php $width = $width > 560 ? 560 : $width; ?>
                <p class="centerText">
                    <a target="_blank" href="{{ isset($bloc->lien) && !empty($bloc->lien) ? $bloc->link_or_url : url('/') }}">
                        <img style="width: {{ $width }}px;" alt="{{ $bloc->titre ?? '' }}" src="{{ secure_asset(config('newsletter.path.upload').$bloc->image) }}" />
                    </a>
                </p>
                {!!  $bloc->titre ? '<h2 class="centerText">'. $bloc->titre.'</h2>' : '' !!}
            @endcomponent

        </td>
    </tr>
    <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr>
</table>


