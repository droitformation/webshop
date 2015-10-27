<div class="form-group">
    <label class="col-sm-3 control-label">{{ $title }}</label>
    <div class="col-sm-6">
        <ul class="list-group">
            @if(!$prices->isEmpty())
                @foreach($prices as $prix)
                    <li class="list-group-item">{{ $prix->description }} <span class="label label-default pull-right">{{ $prix->price_cents }} CHF</span></li>
                @endforeach
            @endif
        </ul>
        <div class="collapse" id="price{{ $type }}">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control" type="text" name="description" value="" placeholder="Remarque / Profession">
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control"  name="price" placeholder="Prix">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <span class="input-group-btn"><button class="btn btn-info" type="button">Ajouter</button></span>
                    </div><!-- /input-group -->
                </div>
            </div><hr/>
        </div>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#price{{ $type }}" type="button">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>
    </div>
</div>