<div class="form-group">
    <label class="col-sm-3 control-label">Options</label>
    <div class="col-sm-8">

        <ul class="list-group">
            @if(!$colloque->options->isEmpty())
                @foreach($colloque->options as $option)
                    <li class="list-group-item">
                        <div class="row">
                            <?php $type = $option->type == 'choix' ? true : false; ?>
                            <div class="col-md-{{ $type ? 5 : 11 }}">
                                <a class="editableOption" data-name="title" data-model="option" data-type="text" data-pk="{{ $option->id }}" data-url="admin/colloque/editoption" data-title="Changer le titre">
                                    {{ $option->title }}
                                </a>
                            </div>
                            @if($type && !$option->groupe->isEmpty())
                                <div class="col-md-6">
                                    @foreach($option->groupe as $group)
                                        <p>
                                            <a class="editableOption" data-name="title" data-model="group" data-type="text" data-pk="{{ $group->id }}" data-url="admin/colloque/editoption" data-title="Modifier le choix">
                                                {{ $group->text }}
                                            </a>
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                            <div class="col-md-1">
                                <button class="btn btn-xs btn-danger pull-right removeOption" data-id="{{ $option->id }}" type="button">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>
                            </div>
                        </div>

                    </li>
                @endforeach
            @endif
        </ul>
        <div class="collapse" id="option">
            <div class="row option">

                <div class="col-md-4">
                    <select name="type" id="selectTypeOption" class="form-control">
                        <option value="checkbox">Case à cocher</option>
                        <option value="choix">Choix</option>
                        <option value="text">Texte</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="title" placeholder="Titre">
                        <span class="input-group-btn">
                            <button class="btn btn-info addOption" type="button">Ajouter</button>
                        </span>
                    </div><!-- /input-group -->
                    <div id="optionGroupe" style="display: none;">
                        <p><textarea name="group" class="form-control" style="height: 100px;" placeholder="Une option par ligne..."></textarea></p>
                    </div>
                </div>

            </div><hr/>
        </div>
    </div>
    <div class="col-sm-1">
        <button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#option" type="button">&nbsp;&nbsp;<i class="fa fa-plus"></i>&nbsp;&nbsp;</button>
    </div>
</div>