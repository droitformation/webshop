<div class="form-group">
    <label class="col-sm-3 control-label">Options</label>
    <div class="col-sm-7">
        <ul class="list-group">
            @if(!$colloque->options->isEmpty())
                @foreach($colloque->options as $option)
                    <li class="list-group-item">
                        <a class="editableOption" data-name="title" data-type="text" data-pk="{{ $option->id }}" data-url="admin/colloque/editoption" data-title="Changer le titre">
                            {{ $option->title }}
                        </a>
                        <button class="btn btn-xs btn-danger pull-right removeOption" data-id="{{ $option->id }}" type="button">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="collapse" id="option">
            <div class="row option">
                <div class="col-md-4">
                    <select name="type" class="form-control">
                        <option value="checkbox">Case à cocher</option>
                        <option value="choix">Choix</option>
                        <option value="text">Texte</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control"  name="title" placeholder="Titre">
                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                        <span class="input-group-btn">
                            <button class="btn btn-info addOption" type="button">Ajouter</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div><hr/>
        </div>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#option" type="button">&nbsp;&nbsp;<i class="fa fa-plus"></i>&nbsp;&nbsp;</button>
    </div>
</div>