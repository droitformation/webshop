<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Colloques</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Colloques">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="token" content="<?php echo csrf_token(); ?>">

    @include('partials.commonjs')

    <!-- Colloques Files -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/colloque/colloque.css');?>" media="screen" />

    <script src="<?php echo asset('js/colloque/isotope.pkgd.min.js');?>"></script>
    <script src="<?php echo asset('js/colloque/inscription.js');?>"></script>


    <base href="/">
</head>
<body>

    <div class="container">

        <!-- Navigation  -->
        @include('frontend.partials.header')
        @include('partials.message')

        <!-- Contenu -->
        @yield('content')
        <!-- Fin contenu -->

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.10/vue.js"></script>
    <script src="<?php echo asset('js/colloque/vue-resource.js');?>"></script>
    <script src="<?php echo asset('js/colloque/grid.js');?>"></script>

</body>
</html>
