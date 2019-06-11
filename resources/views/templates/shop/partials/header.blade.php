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
                <ul id="facdroit">
                    <li>{{ \Registry::get('shop.infos.nom') }}</li>
                    <li class="mb-5">{!! \Registry::get('shop.infos.adresse') !!}</li>
                    {!! !empty(\Registry::get('shop.infos.telephone')) ? '<li>Tél. '.\Registry::get('shop.infos.telephone').'</li>' : '' !!}
                    {!! !empty(\Registry::get('shop.infos.email')) ? '<li>'.\Registry::get('shop.infos.email').'</li>' : '' !!}
                </ul>
            </td>
            <td align="top" width="40%" valign="top">
                @if($generate->getAdresse())
                    @include('templates.partials.adresse',['adresse' => $generate->getAdresse()])
                @endif
                @if($generate->getReferences())
                    <div id="user_reference">
                        {!! !empty($generate->getReferences()->reference_no) ? '<p>Votre référence: <i>'.$generate->getReferences()->reference_no.'</i></p>' : '' !!}
                        {!! !empty($generate->getReferences()->transaction_no) ? '<p>N° commande: <i>'.$generate->getReferences()->transaction_no.'</i></p>' : '' !!}
                    </div>
                @endif
            </td>
        </tr>
    </table>
</div>