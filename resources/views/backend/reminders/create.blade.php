@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/reminder')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <?php $config = config('reminder.'.$type); ?>

            <!-- form start -->
            <form action="{!!  url('admin/reminder')!!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">{!! csrf_field() !!}

            <div class="panel-body event-info">
                <h4>Ajouter un rappel</h4>
                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-7">
                        <input type="text" name="title" value="" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">

                    <label for="message" class="col-sm-3 control-label">Depuis</label>
                    <div class="col-sm-7">
                        @if(!empty($config))
                            <select class="form-control" name="start">
                                @foreach($config['dates'] as $date => $human)
                                    <option value="{{ $date }}">{{ $human }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">

                    <label for="message" class="col-sm-3 control-label">Interval</label>
                    <div class="col-sm-7">
                        <select class="form-control" name="duration">
                            <option value="">Choix</option>
                            <option value="week">1 semaine</option>
                            <option value="month">1 mois</option>
                            <option value="trimester">3 mois</option>
                            <option value="semester">6 mois</option>
                            <option value="year">1 an</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">

                    <label for="message" class="col-sm-3 control-label">Email de rappel pour {{ $config['name'] }}</label>
                    <div class="col-sm-7">

                        @if(!$items->isEmpty())
                            <select class="form-control" name="model_id">
                                @foreach($items->sortBy('title') as $item)
                                    <option value="{{ $item->id }}">{{ $item->title ?? $item->titre }}</option>
                                @endforeach
                            </select>
                        @endif

                    </div>
                </div>

                <div class="form-group">
                    <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                    <div class="col-sm-7">
                        <textarea name="text" class="form-control redactor"></textarea>
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                <div class="col-sm-3">
                    @if(isset($config['model']))
                        <input type="hidden" name="model" value="{{ $config['model'] }}">
                    @endif
                    <input type="hidden" name="type" value="{{ $type }}">
                </div>
                <div class="col-sm-9">
                    <button class="btn btn-primary" id="createReminder" type="submit">Envoyer</button>
                </div>
            </div>

           </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop