<!-- This is the main wrapper for ajax -->
<div class="form-group">
    <label class="col-sm-3 control-label">Multiples conférences</label>
    <div class="col-sm-8">

        <ul class="list-group">
            @if(!$colloque->occurrences->isEmpty())
                @foreach($colloque->occurrences as $occurrence)

                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-8">
                                <p><strong>Titre</strong></p>
                                <a class="editableItem"
                                   data-name="title"
                                   data-model="occurrence" data-type="text"
                                   data-pk="{{ $occurrence->id }}" data-url="admin/colloque/editItem"
                                   data-title="Changer le titre">{{ $occurrence->title }}
                                </a>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Date</strong></p>
                                <a class="editableItem"
                                   data-name="starting_at"
                                   data-model="occurrence" data-type="date"
                                   data-pk="{{ $occurrence->id }}" data-url="admin/colloque/editItem"
                                   data-title="Changer la date">{{ $occurrence->starting_at->format('Y-m-d') }}
                                </a>
                            </div>
                            <div class="col-md-1 text-right">
                                <button class="btn btn-xs btn-danger pull-right removeItem" data-model="occurrence" data-view="occurrences" data-id="{{ $occurrence->id }}" type="button">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Lieu</strong></p>
                                <a class="editLocation" href="#" data-type="select"
                                   data-model="occurrence" data-name="lieux_id"
                                   data-pk="{{ $occurrence->id }}"
                                   data-url="admin/colloque/editItem" data-title="Lieu">
                                </a>
                                <script>
                                    jQuery(document).ready(function($){
                                        $('.editLocation').editable({
                                            value : <?php echo json_encode($occurrence->location_id); ?>,
                                            source: <?php echo json_encode($locations_json); ?>,
                                            params: function(params) {
                                                params._token = $("meta[name='_token']").attr('content');
                                                params.model  = $(this).editable().data('model');
                                                return params;
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="collapse" id="occurrence">

            <div class="occurrence itemWrapper">
                <div class="row">
                    <div class="col-md-7">
                        <label>Lieu</label>
                        <select class="form-control form-required required" name="lieux_id">
                            @if(!$locations_colloque->isEmpty())
                                <option value="">Choix</option>
                                @foreach($locations_colloque as $location)
                                    <option {{ ($colloque->location_id == $location->id ? 'selected' : '') }} value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label>Date</label>
                        <input type="text" name="starting_at" class="form-control datePicker required" value="">
                    </div>
                </div><br/>
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="title" placeholder="Titre">
                            <span class="input-group-btn">
                                <button class="btn btn-info addItem" data-model="occurrence" data-view="occurrences" type="button">Ajouter</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
        </div>
    </div>
    <div class="col-sm-1">
        <button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#occurrence" type="button">&nbsp;&nbsp;<i class="fa fa-plus"></i>&nbsp;&nbsp;</button>
    </div>
</div>