<tr>
    <td width="600" align="center" valign="top">

        <!-- Logos and header img -->
        <table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset">
            <tr class="resetMarge" style="display:block;">
                <td width="600" style="margin: 0;padding: 0;display:block;border: 1px solid #ededed; border-bottom: 0;line-height: 0;">
                    <a href="{{ url('/') }}">
                        <?php list($width, $height) = getimagesize(public_path('newsletter/'.$infos->newsletter->logos )); ?>
                        <img width="{{ $width }}" height="{{ $height }}" style="display:block;margin: 0;padding: 0;" alt="{{ $infos->newsletter->from_name }}" src="{{ asset('newsletter/'.$infos->newsletter->logos ) }}" />
                    </a>
                </td>
            </tr>
            <tr class="resetMarge" style="display:block;">
                <td width="600" class="resetMarge" style="margin: 0;padding: 0;display:block;border: 0; line-height: 0;">
                    <?php list($width, $height) = getimagesize(public_path('newsletter/'.$infos->newsletter->header )); ?>
                    <img width="{{ $width }}" height="{{ $height }}" alt="{{ $infos->newsletter->from_name }}" src="{{ asset('newsletter/'.$infos->newsletter->header ) }}" />
                </td>
            </tr>
        </table>
        <!-- End logos and header img -->

    </td>
</tr>
