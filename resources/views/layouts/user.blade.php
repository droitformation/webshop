<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Profil</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Colloques">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="token" content="<?php echo csrf_token(); ?>">

    @include('partials.commonjs')

    <!-- Colloques Files -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/user/profil.css');?>" media="screen" />

    <base href="/">
</head>
<body>

    <div class="container">

        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        Droit formation
                    </a>
                </div>
            </div>
        </nav>

        @include('partials.message')

        <div class="container-fluid main-container">

            <!-- Navigation  -->
            @include('users.partials.nav')

            <!-- Contenu -->
            @yield('content')
            <!-- Fin contenu -->

        </div>
    </div>
</body>
</html>
