<!-- Modal -->
<div class="modal fade" id="editGroup_{{ $group->id }}" tabindex="-1" role="dialog" aria-labelledby="editGroup">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/inscription/change') }}" method="POST" class="form" >
                {!! csrf_field() !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Changer le détenteur du groupe</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-warning" role="alert">
                        <p><strong>Attention!</strong> Êtes-vous sûr de vouloir changer le détenteur?</p>
                    </div>

                    <h4>Détenteur actuel</h4>
                    <address>
                        @if(isset($user))
                            <?php $adresse = $user->adresses->where('type',1)->first();?>
                            {{ $adresse->company }}<br/>
                            {{ $adresse->name }}<br>
                            {{ $adresse->adresse }}<br/>
                            {{ $adresse->npa }} {{ $adresse->ville }}
                        @endif
                    </address>

                    <div class="form-group">
                        <label><strong>Colloque</strong></label>
                        <p>{{ $group->colloque->titre }}</p>
                    </div>

                    <!-- Search user autocomplete -->
                    @include('backend.partials.search-user')

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="group_id" value="{{ $group->id }}" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Changer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->