<!DOCTYPE html>
<html lang="fr">
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/common.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/bon.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('common/css/generate/invoice.css') }}?{{ rand(245,654323) }}" media="screen" />
</head>
<body style="position: relative;height:297mm;">

    <!-- Contenu -->
    @yield('content')
    <!-- Fin contenu -->

</body>
</html>