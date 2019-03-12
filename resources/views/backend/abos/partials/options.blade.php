<div class="mb-40">
    <form action="{{ url('admin/abo/export') }}" method="POST" class="pull-left" style="width: 350px;">{!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $abo->id }}">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">Exporter</span>
                <select class="form-control" name="status">
                    <option {{ old('status') == 'tous' ? 'selected' : '' }} value="">Tous</option>
                    <option {{ old('status') == 'abonne' ? 'selected' : '' }} value="abonne">Abonné</option>
                    <option {{ old('status') == 'tiers'  ? 'selected' : '' }} value="tiers">Tiers</option>
                    <option {{ old('status') == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                </select>
                <span class="input-group-btn">
               <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> &nbsp;OK</button>
            </span>
            </div>
        </div>
    </form>
    <div class="btn-group pull-right">
        <a href="{{ url('admin/abonnement/create/'.$abo->id) }}" class="btn btn-success" id="addAbonne"><i class="fa fa-plus"></i> &nbsp;Ajouter un abonné</a>
        <a class="btn btn-warning" href="{{ url('admin/abo/desinscription/'.$abo->id) }}">Désabonnements</a>
    </div>
    <div class="clearfix"></div>
</div>
