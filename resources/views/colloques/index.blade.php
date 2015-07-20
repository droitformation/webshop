@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Colloques</h2>
            <p>&nbsp;</p>
        </div>
    </div>

    @if(!$colloques->isEmpty())
        <?php $colloques = $colloques->chunk(2); ?>
        @foreach($colloques as $row)
            <div class="row">
            @foreach($row as $colloque)

                <div class="col-md-6 col-xs-12">

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-left">
                                    <a href="#" class="thumbnail">
                                        <img style="width: 80px;" class="media-object" src="{{ asset($colloque->illustration) }}" alt="" />
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{ $colloque->titre }}</h4>
                                    <p>{{ $colloque->soustitre }}</p>
                                    <p>{{ $colloque->sujet }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-success">Editer</button>
                                <button type="button" class="btn btn-info">Inscriptions</button>
                            </div>
                        </div>
                    </div>

                </div>

            @endforeach
            </div>
        @endforeach
    @endif

@stop