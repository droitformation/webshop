<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style>
            * {
                font-family: Arial, Helvetica, sans-serif;
                text-align: justify;
                box-sizing:border-box;
            }
            @page {
                padding: 20px; margin:20px 50px; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: avoid;
            }
            .bloc{
                margin: 10px 0;
                width: 100%;
                page-break-inside: avoid !important;
            }
            .bloc,
            .bloc div,
            .bloc div p,
            .bloc div p a,
            .bloc p
            .bloc a,
            .bloc ul li
            {
                font-size: 14px !important;
                line-height:17px;
                box-sizing:border-box;
                page-break-inside: avoid !important;
            }

            .bloc img,
            .bloc div img{
                margin: 10px 0;
                display: block;
            }

            h1{
                font-size: 26px;
                padding: 0 5px;
                margin: 0;
                color: #5A101F;
            }

            .arret h2,
            .analyse h2 {
                font-size: 20px;
                margin: 0;
                color: {{ $campagne->newsletter->color }};
            }

            h4 {
                font-size: 16px;
                margin: 0;
            }

            .arret p{
                margin-bottom: 5px;
            }

            .arret, .analyse{
                width: 100%;
                display: block;
                padding: 0 5px;
            }

            .arret-content{
                display: block;
                box-sizing:border-box;
                width: 100%;
            }
            .arret-categories{
                display: inline-block;
                width:19%;
                box-sizing:border-box;
                text-align: center;
            }
            .arret-categories a{
                display: block;
                width:100%;
                text-align: center;
                margin-bottom: 3px;
            }

            hr{
                display: block;
                clear: both;
                height: 1px;
                margin: 5px 0;
                visibility: hidden;
            }

            .header{
                margin-bottom: 30px;
                padding: 5px 10px;
                background: {{ $campagne->newsletter->second_color ? $campagne->newsletter->second_color : $campagne->newsletter->color }};
            }

            .header h1{
                margin:0 0 2px 0;
                font-size: 22px;
                padding: 5px 0;
                color: #fff;
            }

            .header h2{
                margin:0 0 2px 0;
                font-size: 18px;
                padding: 0;
                color: #fff;
            }

            .header h3{
                margin-bottom: 0;
                padding: 0;
                font-size: 16px;
                color: #fff;
                font-weight: normal;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <!-- Main content -->
            @yield('content')
            <!-- Fin contenu -->
        </div>
    </body>
</html>