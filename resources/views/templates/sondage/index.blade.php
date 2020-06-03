<html>
<head>
    <style type="text/css">
        @page { margin: 10mm; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-before: always;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/common.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/bon.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/invoice.css') }}" media="screen" />
    <style>
        *{
            font-size: 13px;
            line-height: 19px;
            font-weight: 400;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body style="padding: 2mm; position: relative;">

<div class="content">

    <h1 style="margin:20px 0; font-size: 22px;">Réponses au sondage</h1>

    @if($sondage->marketing)
        <h3 style="margin-top: 5px; font-size: 18px;">{{ $sondage->title }}</h3>
        {!! $sondage->description !!}
    @else
        <p><strong>{{ $sondage->colloque->titre }} | {{ $sondage->colloque->event_date }}</strong></p>
    @endif

    @if(!$reponses->isEmpty())

    <!-- Sort by responses -->
        @if($sort == 'avis_id')
            @foreach($reponses as $avis)

                @if(isset($avis['title']))
                    <div style="padding: 10px 0; font-size: 16px; font-weight: bold;">{!! strip_tags($avis['title']) !!}</div>
                @endif

                @if(isset($avis['chapitre']))
                    <h4 style="padding: 5px 0;font-size: 16px;">{!! strip_tags($avis['chapitre']) !!}</h4>
                @endif

                @if(isset($avis['reponses']) && !$avis['reponses']->isEmpty())
                    @if($avis['type'] != 'text')

                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px; display: table">
                            <?php
                                $reponses = $avis['reponses'];
                                $reponses = sortArrayByArray($reponses->toArray(), ['Excellent','Bon','Satisfaisant','Insatisfaisant']);
                            ?>
                            @foreach($reponses as $q => $note)
                                <tr>
                                    <td>{{ $q }}: </td>
                                    <td>{{ $note }}</td>
                                </tr>
                            @endforeach
                        </table>

                    @else
                        @foreach($avis['reponses'] as $note)
                            <p>{!! strip_tags($note) !!}</p>
                        @endforeach
                    @endif
                @endif

            @endforeach
        @endif

        @if($sort == 'reponse_id')
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px; display: table">
                @foreach($reponses as $id => $response)
                    <tr valign="top">
                        <td valign="top"><h4 style="font-weight: bold;margin: 0;">{{ $response->first()->response->email }}</h4></td>
                        <td valign="top">
                            @foreach($response->sortByDesc('avis_id') as $avis)
                                <div style="margin-bottom:5px; font-weight: bold;font-size: 15px;">{!! strip_tags($avis->avis->question) !!}</div>
                                <div>{!! strip_tags($avis->reponse) !!}</div>
                            @endforeach
                        </td>
                    </tr>
                    <tr valign="top"><td>&nbsp;</td><td>&nbsp;</td></tr>
                @endforeach
            </table>
        @endif
    @endif

</div>

</body>
</html>