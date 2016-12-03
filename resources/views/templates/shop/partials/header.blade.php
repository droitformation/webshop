<div id="header-main">
    <table id="content-table">
        <tr><td colspan="2" height="30">&nbsp;</td></tr>
        <tr>
            <td colspan="2">
                <img height="70mm" id="logoImg" src="{{ public_path('files/main/'.\Registry::get('shop.infos.logo')) }}" alt="Unine logo" />
            </td>
        </tr>
        <tr><td colspan="2" height="10">&nbsp;</td></tr>
        <tr align="top">
            <td align="top" width="60%" valign="top">
                <div id="facdroit">
                    <li>{{ \Registry::get('shop.infos.nom') }}</li>
                    <li class="mb-5">{!! \Registry::get('shop.infos.adresse') !!}</li>
                    {!! !empty(\Registry::get('shop.infos.telephone')) ? '<li>Tél. '.\Registry::get('shop.infos.telephone').'</li>' : '' !!}
                    {!! !empty(\Registry::get('shop.infos.email')) ? '<li>'.\Registry::get('shop.infos.email').'</li>' : '' !!}
                </div>
            </td>
            <td align="top" width="40%" valign="top">
                @if($adresse)
                    <ul id="user">
                        {!! (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '') !!}
                        <li>{{ $adresse->civilite_title.' '.$adresse->name }}</li>
                        <li>{{ $adresse->adresse }}</li>
                        {!! (!empty($adresse->complement) ? '<li>'.$adresse->complement.'</li>' : '') !!}
                        {!! (!empty($adresse->cp) ? '<li>'.$adresse->cp_trim.'</li>' : '') !!}
                        <li>{{ $adresse->npa }} {{ $adresse->ville }}</li>
                    </ul>
                @endif
            </td>
        </tr>
    </table>
</div>