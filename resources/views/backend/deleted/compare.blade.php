@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Adresses comparer</h3>
    </div>
    <div class="col-md-6 text-right"></div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class='examples'>
            <div class='parent'>
                <div class='wrapper'>
                    <div id='middle-defaults' class='wide'>

                        @if(!$adresses->isEmpty())
                            @foreach($adresses as $adresse)
                                <?php $color = $adresse->user_id > 0 ? 'info' : 'success'; ?>
                                <?php $heading = $adresse->user_id > 0 ? 'Compte + adresse' : 'Adresse simple'; ?>
                               @include('backend.deleted.partials.adresse', ['adresse' => $adresse, 'color' => $color, 'heading' => $heading])
                            @endforeach
                        @endif

                    </div>
                </div>

                <div class='wrapper'>
                    <div id='left-defaults' class='container_dd'>

                    </div>
                    <div id='right-defaults' class='container_dd'>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form" id="transvaseForm">
                            <div class="row">
                                <div class="col-md-2">

                                   <div class="form-group">
                                       <label for="exampleInputName2"><strong>Transvaser</strong></label>
                                       <select style="max-height: 80px;" multiple class="form-control">
                                           <option value="orders">Commandes</option>
                                           <option value="inscriptions">Inscriptions</option>
                                           <option value="abos">Abos</option>
                                       </select>
                                   </div>

                               </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for=""><strong>Ensuite</strong></label>
                                        <div class="checkbox">
                                            <label><input name="action" type="radio" value="delete" checked> &nbsp;Supprimer l'adresse et/ou compte</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input name="action" type="radio" value=""> &nbsp;Garder l'adresse et/ou compte</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input name="action" type="radio" value="attach"> &nbsp;Attacher l'adresse au compte</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" id="ids" value="">
                                    <input type="hidden" id="transvase_id" value="">
                                    <button type="submit" class="btn btn-orange">Transvaser</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


@stop