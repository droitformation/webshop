<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Téléchargement document</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Droit Formation | 404">
    <meta name="author" content="Cindy Leschaud | @DesignPond">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('backend/css/styles.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/common/css/404.css');?>">

    <style>
        h3{
            color: #3b3b3b;
        }
        h4{
            color: #3b3b3b;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-push-3 text-center">
            <p>
                <a href="{{ url('pubdroit/colloque/'.$colloque->id) }}">
                    <?php $illustraton = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                    <img height="150px" src="{{ secure_asset('files/colloques/illustration/'.$illustraton) }}" alt=""/>
                </a>
            </p>

            <h3>{{ $colloque->titre }}</h3>
            <p><i class="fa fa-calendar"></i> &nbsp;{{ $colloque->event_date }}</p>

            @if(!$colloque->slides->isEmpty())
                @if(date('Y-m-d') >= $colloque->start_at->subDays(15)->toDateString() )
                    <h4>Documents en téléchargement</h4><br/>
                    <div class="well">
                        @foreach($colloque->slides as $slide)
                            <p>
                                <a target="_blank" href="{{ $slide->getUrl() }}" class="btn btn-primary">
                                    <i class="fa fa-download"></i> &nbsp;{{ $slide->getCustomProperty('title', $slide->name) }}
                                </a>
                            </p>
                        @endforeach
                    </div>
                @else
                    <p style="color: #000;margin-top: 20px;">Les documents ne sont pas disponibles.</p>
                @endif
            @else
                <p style="color: #000; margin-top: 20px;">Encore aucun document disponible.</p>
            @endif

        </div>
    </div>
</div>
</body>
</html>


