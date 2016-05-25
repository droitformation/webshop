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
                line-height: 20px;
                font-size: 15px;
                max-resolution: 0;
                padding: 0;
                display: block;
            }

        </style>
    </head>
    <body>

        @if(!empty($data))
            @foreach($data as $table)
                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="ddd" style="page-break-after:always;">
                   @foreach($table as $row)
                        <tr class="normalize">
                        @foreach($row as $name)
                            <td width="{{ $width }}" height="{{ $height }}" class="normalize height" >
                               <div style="width: 80%;margin: 0 auto; display: block;text-align: center; font-size: 14px;">
                                   {!! !empty($name) ? '<span>'.$name.'</span>' : '' !!}
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