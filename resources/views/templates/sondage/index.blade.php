<html>
<head>
    <style type="text/css">
        @page { margin: 10mm; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/common.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/bon.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/invoice.css') }}" media="screen" />
</head>
<body>

<div class="content">

    <h3>Réponses au sondage</h3>

    @if($sondage->marketing)
        <h4>{{ $sondage->title }}</h4>
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
                                    <td>{{ $q }}: </td>
                                    <td>{{ $note }}</td>
                                </tr>
                            @endforeach
                        </table>

                    @else

                        <ul>
                            @foreach($avis['reponses'] as $note)
                                <li>{!! strip_tags($note) !!}</li>
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