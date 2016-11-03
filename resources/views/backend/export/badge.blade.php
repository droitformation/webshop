<html>
    <head>
        <style type="text/css">
            @page { margin: <?php echo $margin; ?>; }
            * {
                box-sizing: border-box;
            }
            .normalize{
                margin: 0;
                padding: 0;
            }
            .height{
                height: <?php echo $height; ?>;
                max-height: <?php echo $height; ?>;;
                page-break-inside:avoid;
                box-sizing: border-box;
                position: relative;
            }
            div{
                width: auto;
                height: auto;
                margin: auto;
            }
            span{
                font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
                color: #000;
                line-height: 16px;
                font-size: 14px;
                max-resolution: 0;
                padding: 0;
                display: block;
            }

        </style>
    </head>
    <body>

        @if(!empty($data))
            @foreach($data as $table)
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="page-break-after:always;">
                   @foreach($table as $row)
                        <tr class="normalize">
                        @foreach($row as $name)
                            <td width="{{ $width }}" height="{{ $height }}" class="normalize height">
                               <div style="width: 80%;margin: 0 auto; display: block;text-align: left;">

                                   <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                       <tr class="normalize">
                                           <td width="35%" class="normalize height">
                                               <?php $logo = isset($colloque) && isset($colloque->adresse) ? $colloque->adresse->logo : \Registry::get('inscription.infos.logo'); ?>
                                               <img style="max-height: 50px" src="{{ asset('files/main/'.$logo) }}" />
                                           </td>
                                           <td width="65%" class="normalize height">
                                               {!! !empty($name) ? '<span>'.$name.'</span>' : '' !!}
                                           </td>
                                       </tr>
                                   </table>

                               </div>
                            </td>
                            @endforeach
                        </tr>
                       @endforeach
                </table>
            @endforeach
        @endif

    </body>
</html>