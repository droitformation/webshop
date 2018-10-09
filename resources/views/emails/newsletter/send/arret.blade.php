
@if(isset($arret))
    <table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset {{ $arret->dumois ? $campagne->newsletter->classe : '' }}">

        <?php $comment = isset($campagne->newsletter) ? $campagne->newsletter->comment :null;?>

        @if($arret->dumois && $comment)
            <tr bgcolor="ffffff"><td height="20"></td></tr>
            <tr bgcolor="ffffff">
                <td>
                    @component('emails.newsletter.send.partials.widebloc', ['width' => '560'])
                        <h2 style="text-align: left;font-family: sans-serif;">{{ $campagne->newsletter->comment_title }}</h2>
                    @endcomponent
                </td>
            </tr>
        @endif

        @if(isset($campagne->newsletter) && $campagne->newsletter->display == 'top')
            @if(!$comment)
                <tr bgcolor="ffffff"><td height="10"></td></tr>
            @endif

            @include('emails.newsletter.send.partials.analyses', ['arret' => $arret])
        @endif

        <tr bgcolor="ffffff"><td height="25"></td></tr>
        <tr align="center" class="resetMarge">
            <td class="resetMarge">


                @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                    @slot('picto')
                        @if(!$arret->categories->isEmpty() )
                            @include('emails.newsletter.send.partials.categories',['categories' => $arret->categories])
                        @endif
                    @endslot

                    @slot('content')
                        <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ $arret->dumois ? 'Arrêt du mois : ' : '' }}{{ $arret->reference }} du {{ $arret->pub_date->formatLocalized('%d %B %Y') }}</h3>
                        <p class="abstract">{!! $arret->abstract !!}</p>
                        <div>{!! $arret->pub_text !!}</div>
                        <p><a target="_blank" href="{{ secure_asset(config('newsletter.path.arret').$arret->file) }}">Télécharger en pdf</a></p>
                    @endslot
                @endcomponent

            </td>
        </tr>

        @if(isset($campagne->newsletter) && $campagne->newsletter->display == 'bottom')
            <tr bgcolor="ffffff"><td height="10"></td></tr>

            @include('emails.newsletter.send.partials.analyses', ['arret' => $arret])
        @endif

        <tr bgcolor="ffffff"><td height="25" class="blocBorder"></td></tr>

    </table>

@endif