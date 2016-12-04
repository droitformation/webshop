@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/factures/'.$product->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux factures</a></p>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-7">
                    <img class="thumbnail" style="height: 50px; float:left; margin-right: 15px;padding: 2px;" src="{{ asset('files/products/'.$product->image) }}" />
                    <h3 style="margin-bottom:0;line-height:24px">{{ $abo->title }}</h3>
                    <p style="margin-bottom: 0;">&Eacute;dition {{ $product->reference.' '.$product->edition }}</p>
                </div>

                <div class="col-md-2">
                    <form action="{{ url('admin/abo/generate') }}" method="POST" class="pull-right">
                        <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="worker" value="rappel">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-file-o"></i> &nbsp;Générer tous les rappels</button>
                    </form>
                </div>
                <div class="col-md-1 text-right">
                    <form action="{{ url('admin/abo/bind') }}" method="POST">
                        <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="worker" value="rappel">
                        <button type="submit" class="btn btn-default" title="Re-attacher tous les rappels"><i class="fa fa-link"></i> &nbsp; Attacher</button>
                    </form>
                </div>
                <div class="col-md-2">
                    <form action="{{ url('admin/rappel/send') }}" method="POST" class="pull-right">{!! csrf_field() !!}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="count" value="{{ !$factures->isEmpty() ? $factures->count() : 0 }}">
                        <button class="btn btn-inverse pull-right" id="confirmSendAboRappels">
                            <i class="fa fa-paper-plane"></i> &nbsp;Envoyer les rappel
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body" id="appComponent">

                    <h3 style="margin-bottom: 30px;" class="pull-left">Rappels pour l'&eacute;dition {{ $product->reference.' '.$product->edition }}</h3>
                    <div class="pull-right">
                        @if(!empty($files))
                            <h4 style="margin-bottom: 10px; margin-top: 0;">Rappels liés</h4>
                            <div class="btn-group">
                                @foreach($files as $file)
                                    <?php $name = explode('/',$file); ?>
                                    <a href="{{ asset($file.'?'.rand(1000,2000)) }}" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-download"></i>&nbsp; {{ end($name) }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="clearfix"></div><br/>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-2">Numero</th>
                                <th class="col-md-3">Status</th>
                                <th class="col-md-1">Date</th>
                                <th class="col-md-6">Rappels</th>
                            </tr>
                            </thead>
                            <tbody class="selects">

                            @if(!$factures->isEmpty())
                                @foreach($factures as $facture)
                                    <tr>
                                        <td>{{ $facture->abonnement->numero }}</td>
                                        <td>
                                            @include('backend.abonnements.partials.payed',['facture' => $facture])
                                        </td>
                                        <td>{{ $facture->created_at->formatLocalized('%d %b %Y') }}</td>
                                        <td>
                                            <rappel path="abonnement" :rappels="{{ $facture->rappel_list }}" item="{{ $facture->id }}"></rappel>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table><!-- End inscriptions -->
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop