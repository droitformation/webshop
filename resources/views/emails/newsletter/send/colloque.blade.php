
<?php $width = isset($isEdit) ? 560 : 600; ?>

@if(isset($bloc->colloque))
    <table border="0" width="{{ $width }}" align="center" cellpadding="0" cellspacing="0" class="tableReset">
        <tr bgcolor="ffffff"><td height="35"></td></tr><!-- space -->
        <tr align="center" class="resetMarge">
            <td class="resetMarge">

                @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                    @slot('picto')
                        <a target="_blank" href="{{ url('pubdroit/colloque/'.$bloc->colloque->id) }}">
                            <img width="130" border="0" alt="{{ $bloc->colloque->titre }}" src="{{ secure_asset($bloc->colloque->frontend_illustration) }}" />
                        </a>
                    @endslot

                    @slot('content')
                            <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ $bloc->colloque->titre }}</h3>
                            <p class="abstract">{!! $bloc->colloque->event_date !!}</p>
                            <p><strong>Lieu: </strong><cite>{{ $bloc->colloque->location ? $bloc->colloque->location->name : '' }}</cite></p>

                            <p><a target="_blank"
                                  style="padding: 5px 10px; text-decoration: none; background: {{ $campagne->newsletter->color }}; color: #fff; margin-top: 10px; display: inline-block;"
                                  href="{{ url('pubdroit/colloque/'.$bloc->colloque->id) }}">Informations et inscription</a></p>
                    @endslot
                @endcomponent

            </td>
        </tr>
        <tr bgcolor="ffffff"><td height="35" class="blocBorder"></td></tr><!-- space -->
    </table>

@endif