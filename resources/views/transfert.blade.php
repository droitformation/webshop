<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">

</head>
    <body>
        <div class="container">
            @include('alert::bootstrap')
            <div class="col-7">
            <div class="card mt-6">
                <div class="card-body">
                    <h1 class="mb-4">Transfert</h1>
                    <form action="{{ url('/dotransfert') }}" method="POST" class="mb-4 form">{!! csrf_field() !!}

                            <div class="row mt-1">
                                <label class="col-3" for="nom">database</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" name="database" value="{{ old('database') }}">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <label class="col-3" for="nom">nom</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" name="nom" value="{{ old('nom') }}">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <label class="col-3" for="nom">url</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" name="url" value="{{ old('url') }}">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <label class="col-3" for="nom">logo</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" name="logo" value="{{ old('logo') }}">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <label class="col-3" for="nom">slug</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" name="slug" value="{{ old('slug') }}">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <label class="col-3" for="nom">prefix</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" name="prefix" value="{{ old('prefix') }}">
                                </div>
                            </div>

                            <button class="btn btn-info btn-sm mt-2" type="submit">Transfert</button>

                    </form>
                </div>
            </div>
            </div>
        </div>

    </body>
</html>