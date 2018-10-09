
<?php $width = isset($isEdit) ? 560 : 600; ?>

@if(isset($bloc->product))
    <table border="0" width="{{ $width }}" align="center" cellpadding="0" cellspacing="0" class="tableReset">
        <tr bgcolor="ffffff"><td height="35"></td></tr>
        <tr align="center" class="resetMarge">
            <td class="resetMarge">

                @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                    @slot('picto')
                        <a target="_blank" href="{{ url('pubdroit/product/'.$bloc->product->id) }}">
                            <img width="130" border="0" alt="{{ $bloc->product->title }}" src="{{ secure_asset('files/products/'.$bloc->product->image) }}" />
                        </a>
                    @endslot

                    @slot('content')
                            <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ $bloc->product->title }}</h3>
                            <p class="abstract">{!!$bloc->product->teaser !!}</p>
                            <div>{!! $bloc->product->description !!}</div>
                            <p><a target="_blank"
                                  style="padding: 5px 15px; text-decoration: none; background: {{ $campagne->newsletter->color }}; color: #fff; margin-top: 10px; display: inline-block;"
                                  href="{{ url('pubdroit/product/'.$bloc->product->id) }}">Acheter</a>
                            </p>
                    @endslot
                @endcomponent

            </td>
        </tr>
        <tr bgcolor="ffffff"><td height="35" class="blocBorder"></td></tr>
    </table>

@endif