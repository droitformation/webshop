<html>
    <head>
        <style type="text/css">
            @page { margin: 1em }
            * {
                box-sizing: border-box;
            }
            .normalize{
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>

        @if(!$inscriptions->isEmpty())
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <?php $chunks = $inscriptions->chunk(2); ?>
                @foreach($chunks as $row)
                    <tr class="normalize">
                        @foreach($row as $code)
                           <td width="50%">
                               <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                   <tr>
                                       <td width="30%"><img src="data:image/png;base64, {{ $code->qrcode }}"></td>
                                       <td width="70%">{{ $code->name_inscription }}<br><small>{{ $code->inscription_no }}</small></td>
                                   </tr>
                               </table>
                           </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        @endif

    </body>
</html>