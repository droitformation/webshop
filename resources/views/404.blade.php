
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>404</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Droit Formation | 404">
    <meta name="author" content="Cindy Leschaud | @DesignPond">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/styles.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/404.css');?>">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>404</h1>
                <h2>Page introuvable</h2>
                <div>
                    <a class="btn btn-primary" href="{{ url('/') }}"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour à publication-droit.ch </a>
                    <a class="btn btn-danger" href="{{ url('/') }}"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour à bail.ch </a>
                    <a class="btn btn-magenta" href="{{ url('/') }}"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour à droitmatrimonial.ch </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


