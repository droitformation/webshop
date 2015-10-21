@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <a href="{{ url('admin/newsletter') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i>  &nbsp;&nbsp;Retour aux newsletter</a>
    </div>
</div>

<div id="main" ng-app="newsletter"><!-- main div for app-->

    <div class="row">
        <div class="col-md-12">

            <input id="campagne_id" value="{{ $infos->id }}" type="hidden">

            <div class="component-build"><!-- Start component-build -->

                <div id="optionsNewsletter">
                    <a href="{{ url('admin/campagne/'.$infos->id.'/edit') }}" class="btn btn-primary btn-block"><i class="fa fa-pencil"></i>  &nbsp;&Eacute;diter la campagne</a>
                    <a target="_blank" href="{{ url('campagne/'.$infos->id) }}" class="btn btn-sky btn-block"><i class="fa fa-eye"></i>  &nbsp;Aperçu de la campagne</a>
                    <hr/>
                    <form action="{{ url('admin/campagne/test') }}" enctype="multipart/form-data" method="POST" class="form">
                        {!! csrf_field() !!}

                        <label><strong>Envoyer un test</strong></label>
                        <div class="input-group">
                            <input required name="email" value="" type="email" class="form-control">
                            <input name="id" value="{{ $infos->id }}" type="hidden">
                            <span class="input-group-btn">
                                <button class="btn btn-brown" type="submit">Go!</button>
                            </span>
                        </div><!-- /input-group -->

                    </form>
                </div>

                <div id="StyleNewsletter" class="onBuild">

                    <!-- Logos -->
                    @include('backend.newsletter.send.logos')
                    <!-- Header -->
                    @include('backend.newsletter.send.header')

                    <div id="viewBuild">
                        <div id="sortable">
                            @if(!empty($campagne))
                                @foreach($campagne as $bloc)
                                    <div class="bloc_rang" id="bloc_rang_{{ $bloc->idItem }}" data-rel="{{ $bloc->idItem }}">
                                        <?php echo view('backend.newsletter/build/edit/'.$bloc->type->partial)->with(array('bloc' => $bloc, 'categories' => $categories, 'imgcategories' => $imgcategories))->__toString(); ?>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>

                <div id="build"><!-- Start build -->

                    @if(!empty($blocs))
                        @foreach($blocs as $bloc)
                            <div class="create_bloc" id="create_{{ $bloc->id }}">
                                <?php echo view('backend/newsletter/build/create/'.$bloc->template)->with(array('bloc' => $bloc, 'infos' => $infos, 'categories' => $categories, 'imgcategories' => $imgcategories))->__toString(); ?>
                            </div>
                        @endforeach
                    @endif

                    <div class="component-menu">
                        <h5>Composants</h5>
                        <a name="componant"></a>
                        <div class="component-bloc">
                            @if(!empty($blocs))
                                @foreach($blocs as $bloc)
                                      <?php echo view('backend/newsletter/build/blocs')->with(array('bloc' => $bloc))->__toString(); ?>
                                @endforeach
                            @endif
                        </div>
                    </div><!-- End build -->

                </div>
            </div><!-- End component-build -->

        </div><!-- end 12 col -->
    </div><!-- end row -->

</div><!-- end main div for app-->

@stop