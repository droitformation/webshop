<?php
    $prices = $colloque->prices->filter(function ($item) use ($type)
    {
        return $item->type == $type;
    });
?>

<div class="form-group">
    <label class="col-sm-3 control-label">{{ $title }}</label>
    <div class="col-sm-8">
        <ul class="list-group">
            @if(!$prices->isEmpty())
                @foreach($prices as $prix)
                    <li class="list-group-item">

                        <div class="row">
                            <div class="col-md-2">
                                <span class="label label-default">
                                     <a class="editablePrice" data-name="price" data-type="text" data-pk="{{ $prix->id }}" data-url="admin/price/{{ $prix->id }}" data-title="Changer le prix">
                                         {{ $prix->price_cents }}
                                     </a> CHF
                                </span>
                            </div>
                            <div class="col-md-9">
                                <dl class="dl-horizontal price-list">
                                    <dt> Description:</dt>
                                    <dd>
                                        <a class="editablePrice" data-name="description" data-type="text" data-pk="{{ $prix->id }}" data-url="admin/price/{{ $prix->id }}" data-title="Changer la description">
                                            {{ $prix->description }}
                                        </a>
                                    </dd>
                                    <dt> Remarque:</dt>
                                    <dd>
                                        <a class="editablePrice editablePriceRemarque" data-name="remarque" data-type="text" data-pk="{{ $prix->id }}" data-url="admin/price/{{ $prix->id }}" data-title="Changer la remarque">
                                            {{ $prix->remarque }}
                                        </a>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-xs btn-danger pull-right removePrice" data-id="{{ $prix->id }}" type="button">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>
                            </div>
                        </div>

                    </li>
                @endforeach
            @endif
        </ul>
        <div class="collapse" id="price{{ $type }}">
            <div class="row price">
                <div class="col-md-4">
                    <input class="form-control" type="text" name="description" value="" placeholder="Description / Profession">
                </div>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="remarque" value="" placeholder="Remarque">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control"  name="price" placeholder="Prix">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                        <span class="input-group-btn">
                            <button class="btn btn-info addPrice" type="button">Ajouter</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div><hr/>
        </div>
    </div>
    <div class="col-sm-1">
        <button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#price{{ $type }}" type="button">&nbsp;&nbsp;<i class="fa fa-plus"></i>&nbsp;&nbsp;</button>
    </div>
</div>
