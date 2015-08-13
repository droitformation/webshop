@extends('layouts.colloque')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Inscription</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img width="170px" style="margin-right: 20px;" class="media-object img-thumbnail" src="{{ asset($colloque->illustration) }}" alt="{{ $colloque->titre }}">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{ $colloque->titre }}<br/>{{ $colloque->soustitre }}</h4>
                    <p><strong>{{ $colloque->event_date }}</strong> </p>
                    {{-- <hr/>
                    <p><strong>Lieu:</strong> {{ $colloque->location->name }}, {{ $colloque->location->adresse }}</p>
                    <p><strong>Délai d'inscription:</strong> {{ $colloque->registration_at->formatLocalized('%d %B %Y') }}</p>--}}
                    <div class="row">
                        <div class="col-md-7">

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form role="form" class="validate-form" method="POST" action="registration" data-validate="parsley" >
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <fieldset>
                                            @if(!$colloque->prices->isEmpty())
                                            <div class="form-group">
                                                    <label class="control-label">Choix du prix applicable</label>
                                                    <!-- Only public prices -->
                                                    <?php  $filtered = $colloque->prices->reject(function ($item) {return $item->type == 'admin'; });?>
                                                    <select required name="price_id" class="form-control">
                                                        <option value="">Choix</option>
                                                        @foreach($filtered as $price)
                                                            <option value="{{ $price->id }}">{{ $price->description }} | <strong>{{ $price->price_cents }} CHF</strong></option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            @if(!$colloque->options->isEmpty())
                                                <?php $group = $colloque->options->groupBy('type'); ?>
                                                <h4>Merci de préciser</h4>
                                                @foreach($group as $type => $options)

                                                    <!-- Options checkboxes -->
                                                    @if($type == 'checkbox')
                                                        <div class="well well-sm">
                                                        @foreach($options as $option)
                                                            <div class="form-group">
                                                                <input type="checkbox" name="options[]" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}
                                                            </div>
                                                        @endforeach
                                                        </div>
                                                    @endif

                                                    <!-- Options radio -->
                                                    @if($type == 'choix')
                                                        @foreach($options as $option)
                                                        <div class="well well-sm">
                                                            <div class="form-group">
                                                                <label class="control-label">{{ $option->title }}</label>
                                                                <?php $option->load('groupe'); ?>
                                                                @if(!$option->groupe->isEmpty())
                                                                    @foreach($option->groupe as $groupe)
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input type="radio" required name="groupes[{{ $option->id }}]" value="{{ $groupe->id }}">
                                                                                {{ $groupe->text }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    @endif

                                                @endforeach
                                            @endif

                                            <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                                            <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                                            <button class="btn btn-danger pull-right" type="submit">Envoyer</button>
                                        </fieldset>
                                    </form>
                                 </div>
                            </div><!-- end panel -->

                        </div>
                    </div>

                </div>
            </div>

            <?php
               // echo '<pre>';
               // print_r($colloque);
                //echo '</pre>';
            ?>

        </div>
    </div>

@stop