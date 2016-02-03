@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Liste des newsletter</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/newsletter/create') }}" class="btn btn-success" id="addNewsletter"><i class="fa fa-plus"></i> &nbsp;Newsletter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        @if(!empty($newsletters))
            @foreach($newsletters as $newsletter)

                <div class="panel panel-info">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-2">
                                <img height="100%" style="width: 150px;" src="{{ asset('logos/'.$newsletter->site->logo) }}" alt="{{ $newsletter->site->nom }}" />
                            </div>
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-3">
                                <p><i class="fa fa-user"></i> &nbsp; {{ $newsletter->from_name }}</p>
                                <p><i class="fa fa-envelope"></i> &nbsp; {{ $newsletter->from_email }}</p>
                            </div>
                            <div class="col-md-2 text-right">
                                <div class="btn-group-vertical" role="group">
                                    <a href="{{ url('admin/newsletter/'.$newsletter->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> &nbsp;Editer</a>
                                    <a href="{{ url('admin/campagne/create/'.$newsletter->id) }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> &nbsp;Campagne</a>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                @if(!$newsletter->campagnes->isEmpty())

                                    <?php
                                        $campagnes = $newsletter->campagnes->whereLoose('status','brouillon');
                                        $archives  = $newsletter->campagnes->whereLoose('status','envoyé');
                                        $years     = $archives->groupBy(function ($archive, $key) {
                                            return $archive->created_at->year;
                                        });
                                        $annees = array_keys($years->toArray());
                                    ?>

                                    @include('backend.newsletter.campagne.list',['campagnes' => $campagnes])

                                    @foreach($annees as $annee_news)
                                        <a class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#collapseArchives_{{ $newsletter->id.'_'.$annee_news }}">Archives {{ $annee_news }}</a>
                                    @endforeach

                                    @foreach($years as $annee => $year)
                                        <div class="collapse margeUpDown" id="collapseArchives_{{  $newsletter->id.'_'.$annee }}">
                                            <h5><strong>Archive {{ $annee }}</strong></h5>
                                            @include('backend.newsletter.campagne.list',['campagnes' => $year])
                                        </div>
                                    @endforeach

                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <form action="{{ url('admin/newsletter/'.$newsletter->id) }}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <button data-what="supprimer" data-action="newsletter {{ $newsletter->titre }}" class="btn btn-xs btn-default btn-delete deleteAction">Supprimer la newsletter</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            @endforeach
        @endif

    </div>
</div>

@stop
