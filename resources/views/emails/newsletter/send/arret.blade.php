<!-- Bloc -->
<?php $width = isset($isEdit) ? 560 : 600; ?>
@if(isset($bloc->arret))

    <table border="0" width="{{ $width }}" align="center" cellpadding="0" cellspacing="0" class="tableReset {{ $bloc->arret->dumois ? 'alert-dumois' : '' }}">

        @if(isset($campagne->newsletter) && $campagne->newsletter->display == 'top')
            @if($bloc->arret->analyses->isEmpty())
                <tr bgcolor="ffffff"><td height="35" class="blocBorder"></td></tr><!-- space -->
            @endif

            <!-- Analyses -->
            @include('emails.newsletter.send.partials.analyses', ['arret' => $bloc->arret, 'isEdit' => isset($isEdit) ? true : false])
        @endif

        <tr bgcolor="ffffff"><td height="35"></td></tr><!-- space -->
        <tr align="center" class="resetMarge">
            <td class="resetMarge">
                <!-- Bloc content-->

                @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                    @slot('picto')
                        @if(!$bloc->arret->categories->isEmpty() )
                            @include('emails.newsletter.send.partials.categories',['categories' => $bloc->arret->categories])
                        @endif
                    @endslot

                    @slot('content')
                        <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ $bloc->arret->dumois ? 'Arrêt du mois : ' : '' }}{{ $bloc->arret->reference }} du {{ $bloc->arret->pub_date->formatLocalized('%d %B %Y') }}</h3>
                        <p class="abstract">{!! $bloc->arret->abstract !!}</p>
                        <div>{!! $bloc->arret->pub_text !!}</div>
                        <p><a href="{{ secure_asset(config('newsletter.path.arret').$bloc->arret->file) }}">Télécharger en pdf</a></p>
                    @endslot
                @endcomponent

            </td>
        </tr>

        @if(isset($campagne->newsletter) && $campagne->newsletter->display == 'bottom')
            @if($bloc->arret->analyses->isEmpty())
                <tr bgcolor="ffffff"><td height="35" class="blocBorder"></td></tr><!-- space -->
            @endif

            <!-- Analyses -->
            @include('emails.newsletter.send.partials.analyses', ['arret' => $bloc->arret, 'isEdit' => isset($isEdit) ? true : false])
        @endif

        <tr bgcolor="ffffff"><td height="35" class="blocBorder"></td></tr><!-- space -->
    </table>
    <!-- End bloc -->
@endif