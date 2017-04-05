@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Résultat du transfert</h3>
    </div>
    <div class="col-md-6 text-right"></div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class='examples'>
            <div class='parent'>

                <div class='wrapper' id="normal">
                    <div class='container_dd normal-inactiv'>

                        @if(!$deleted->isEmpty())
                            @foreach($deleted as $type => $items)
                                <h4>Supprimé: {{ $type == 'user' ? 'Compte' : 'Adresse' }}</h4>
                                @foreach($items as $item)
                                    @include('backend.deleted.partials.'.$type.'-bloc', [$type => $item])
                                @endforeach
                            @endforeach
                        @endif

                    </div>
                    <div class='container_dd normal-inactiv'>
                        <?php $color = $recipient->user_id > 0 ? 'info' : 'success'; ?>
                        <?php $heading = $recipient->user_id > 0 ? 'Compte + adresse' : 'Adresse simple'; ?>
                        @include('backend.deleted.partials.adresse', ['adresse' => $recipient, 'color' => $color, 'heading' => $heading])
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">

                            <div class="row">
                                <div class="col-md-2">

                                   <div class="form-group">
                                       <label for="exampleInputName2"><strong>Transvaser</strong></label>
                                       @if(!empty($types))
                                           @foreach($types as $type)
                                               {{ ucfirst($type) }}
                                           @endforeach
                                       @endif
                                   </div>

                               </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><strong>Ensuite</strong></label>
                                        @if(!empty($action))
                                           <?php
                                                $actions = [
                                                    'delete' => 'Supprimer l\'adresse et/ou compte',
                                                    'rien' => 'Garder l\'adresse et/ou compte',
                                                    'attach' => 'Attacher l\'adresse au compte',
                                                    'attachdelete' => 'Transvaser l\'adresse et supprimer le compte restant'
                                                ];
                                            ?>
                                            {{ isset($actions[$action]) ? $actions[$action] : '' }}
                                        @endif
                                    </div>
                                </div>

                            </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


@stop