<html>
<head>
    <style type="text/css">
        @page { margin: 10mm; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-before: always;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/common.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/bon.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/invoice.css') }}" media="screen" />
</head>
<body style="padding: 2mm; position: relative;height:297mm;">

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

                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px;">
                            @foreach($avis['reponses'] as $q => $note)
                                <tr>
                                    <td style="padding: 6px; line-height: 16px;">{{ $q }}: </td>
                                    <td style="padding: 6px; line-height: 16px;">{{ $note }}</td>
                                </tr>
                            @endforeach
                        </table>

                    @else

                        <ul style="margin-left: 10px;padding-left: 10px; margin-top: 10px;margin-bottom: 10px; page-break-inside: avoid;">
                            @foreach($avis['reponses'] as $note)
                                <li style="padding: 8px; line-height: 17px;">{!! strip_tags($note) !!}</li>
                            @endforeach
                        </ul>

                    @endif
                @endif

            @endforeach
        @endif

        @if($sort == 'reponse_id')
            @foreach($reponses as $id => $response)

                <table>
                    <tr>
                        <td><h4>{{ $response->first()->response->email }}</h4></td>
                        <td>
                            @foreach($response as $avis)
                                <div>{!! $avis->avis->question !!}</div>
                                <div>{!! $avis->reponse !!}</div>
                            @endforeach
                        </td>
                    </tr>
                </table>

            @endforeach
        @endif
    @endif

</div>

</body>
</html>