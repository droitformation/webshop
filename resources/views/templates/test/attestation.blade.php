<html>
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/common.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/bon.css');?>" media="screen" />
</head>
<body>
<?php $colloque = $generate->getColloque(); ?>
<div class="content">
    <table class="content-table">
        <tr>
            <td><img height="80mm" src="<?php echo public_path('files/logos/facdroit.png'); ?>" alt="Unine logo" /></td>
            <td>
                Neuchâtel, le {{ $date }}
            </td>
        </tr>
        <tr><td colspan="2" height="10">&nbsp;</td></tr>
        <tr align="top">
            <td align="top" width="60%" valign="top">
                @if(!empty($expediteur))
                    <ul id="facdroit">
                        @foreach($expediteur as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @endif

                <p style="margin-top: 20px;">{{ $colloque->attestation->organisateur }}</p>

            </td>
            <td align="top" width="40%" valign="top">
                @include('templates.partials.adresse',['adresse' => $generate->getAdresse()])
            </td>
        </tr>
        <tr><td colspan="2" height="50">&nbsp;</td></tr>
    </table>
</div>

<div class="content">
    <table class="content-table" valign="top">
        <tr>
            <td width="20%"></td>
            <td width="80%" class="content-attestation">
                <h1 class="title blue">{{ strtoupper('attestation') }}</h1>
                <p>{{ $colloque->attestation->organisateur }} atteste que</p>

                <?php $adresse = $generate->getAdresse(); ?>
                @if($adresse)
                    <p><strong>{!! $adresse->civilite_title.' '.$adresse->name !!}</strong></p>
                @endif

                <p>a participé {{ strtolower($colloque->event_date) }} au colloque:</p>
            </td>
        </tr>
        <tr>
            <td width="20%"></td>
            <td width="80%">
                <h4 class="colloque-attestation"><?php echo $colloque->titre; ?></h4>
            </td>
        </tr>
        <tr><td colspan="2" height="10">&nbsp;</td></tr>
        <tr>
            <td width="20%"></td>
            <td width="80%">
                {!! $colloque->attestation->comment  !!}
            </td>
        </tr>
        <tr><td colspan="2" height="50">&nbsp;</td></tr>
    </table>

    <table class="content-table" valign="top">
        <tr>
            <td width="70%"></td>
            <td width="30%" class="signature-attestation">
                <p><strong>{{ $colloque->attestation->title }}</strong></p>
                <p>{{ $colloque->attestation->signature }}</p>
            </td>
        </tr>
    </table>
</div>

</body>
</html>