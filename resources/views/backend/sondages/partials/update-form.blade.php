<form action="{{ url('admin/sondage/'.$sondage->id) }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

    <div class="panel panel-midnightblue">
        <div class="panel-body">

            <h3>Sondage</h3>

            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>Type de sondage</strong></label>
                <div class="col-sm-8">
                    <label class="radio-inline"><input class="typeSondage" type="radio" {{ $sondage->marketing ? 'checked' : '' }} name="marketing" value="1"> Sondage marketing</label>
                    <label class="radio-inline"><input class="typeSondage" type="radio" {{ !$sondage->marketing ? 'checked' : '' }} name="marketing" value=""> Sondage pour colloque</label>
                </div>
            </div>

            <div class="form-group" id="sondageColloque" style="display:{{ $sondage->marketing ? 'none' : 'block' }};">
                <label for="message" class="col-sm-3 control-label">Colloque</label>
                <div class="col-sm-6">
                    <select autocomplete="off" name="colloque_id" class="form-control">
                        <option value="">Choisir le colloque</option>
                        @if(!$colloques->isEmpty())
                            @foreach($colloques as $colloque)
                                <option {{ (old('colloque_id') == $colloque->id ) || ($sondage->colloque_id == $colloque->id) ? 'selected' : '' }} value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div id="sondageMarketing" style="display: {{ !$sondage->marketing ? 'none' : 'block' }};">
                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-6">
                        <input type="text" name="title" value="{{ $sondage->title }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Organisateur</label>
                    <div class="col-sm-6">
                        <input type="text" name="organisateur" value="{{ $sondage->organisateur }}" class="form-control">
                    </div>
                </div>

                @if(!empty($sondage->image ))
                    <div class="form-group">
                        <label for="file" class="col-sm-3 control-label">Bannière</label>
                        <div class="col-sm-3">
                            <div class="list-group">
                                <div class="list-group-item text-center">
                                    <a href="#"><img style="max-width: 100%" src="{!! secure_asset('files/uploads/'.$sondage->image) !!}" alt="{{ $sondage->title }}" /></a>
                                </div>
                                <input type="hidden" name="image" value="{{ $sondage->image }}">
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Changer la bannière<br><small>(max 910px85px)</small></label>
                    <div class="col-sm-7">
                        <div class="list-group">
                            <div class="list-group-item">
                                {!! Form::file('file') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Description du sondage marketing</label>
                    <div class="col-sm-6">
                        <textarea name="description" class="form-control redactorSimple">{{ $sondage->description }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Texte de l'email<br><small>Si vide la description est prise.</small></label>
                    <div class="col-sm-6">
                        <textarea name="email" class="form-control redactorSimple">{{ $sondage->email }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Signature</label>
                    <div class="col-sm-6">
                        <input type="text" name="signature" value="{{ $sondage->signature }}" placeholder="Le secrétariat de la Faculté de droit" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="message" class="col-sm-3 control-label">Valide jusqu'au</label>
                <div class="col-sm-4">
                    <input type="text" name="valid_at" required value="{{ $sondage->valid_at->format('Y-m-d') }}" class="form-control datePicker required">
                </div>
            </div>

        </div>
        <div class="panel-footer mini-footer ">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
                <input type="hidden" name="id" value="{{ $sondage->id }}" />
                <button class="btn btn-primary" type="submit">Envoyer</button>
            </div>
        </div>
    </div>
</form>