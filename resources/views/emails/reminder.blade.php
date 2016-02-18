<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: arial, sans-serif;">

    <div style="font-family: arial, sans-serif;">

        <?php $config = config('jobs.'.$reminder->type); ?>

        <h3 style="color: #252f73;font-size: 18px;font-weight: 300;letter-spacing: 0;line-height: 30px;">{{ $reminder->title }}</h3>
        <p style="color: #000;font-size: 18px;letter-spacing: 0;line-height: 30px;">
            @if($item)
                <strong> Pour le {{ $config['name'] }} :</strong> {{ $item->titre or $item->title }}
            @endif
        </p>
        <p>{!! $reminder->text !!}</p>
        <p><a style="font-family: arial, sans-serif;color: #444; font-size: 13px;" href="{{ url('/') }}">Administration Droit Formation</a></p>
    </div>

</body>
</html>
