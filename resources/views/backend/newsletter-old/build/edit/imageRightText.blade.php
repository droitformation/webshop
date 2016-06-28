<div class="edit_content" ng-controller="EditController as edit"
     flow-init
     flow-fileError="handleErrorsUpload( $file, $message, $flow )"
     flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
     flow-complete="netedited = true"
     flow-files-submitted="$flow.upload()">

    <!-- Bloc content-->
    <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
        <tr bgcolor="ffffff">
            <td colspan="3" height="35">
                <div class="pull-right btn-group btn-group-xs">
                    <button class="btn btn-orange editContent" ng-click="edit.editContent({{ $bloc->idItem }}) && !$flow.files.length" data-id="{{ $bloc->idItem }}"type="button">éditer</button>
                    <button class="btn btn-danger deleteContent deleteContentBloc" data-id="{{ $bloc->idItem }}" data-action="{{ $bloc->titre }}" type="button">&nbsp;×&nbsp;</button>
                </div>
            </td>
        </tr><!-- space -->
        <tr>
            <td valign="top" width="375" class="resetMarge contentForm">
                <h2 ng-bind="edit.titre">{{ $bloc->titre }}</h2>
                <div ng-bind-html="edit.contenu">{{ $bloc->contenu }}</div>
            </td>
            <td width="25" class="resetMarge"></td><!-- space -->
            <td valign="top" align="center" width="160" class="resetMarge">

                <div class="uploadBtn" ng-if="!notedited">
                    <span class="btn btn-xs btn-info" ng-if="edit.onedit( {{ $bloc->idItem }} )" flow-btn flow-attrs="{accept:'image/*'}">Changer image</span>
                    <span class="btn btn-xs btn-warning" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Changer</span>
                    <span class="btn btn-xs btn-danger" ng-show="$flow.files.length" ng-click="$flow.cancel()">Supprimer</span>
                </div>
                <div class="thumbnail mini" ng-hide="$flow.files.length">
                    <img flow-img="$flow.files[0]" ng-if="notedited"/>
                    <?php $lien = (isset($bloc->lien) && !empty($bloc->lien) ? $bloc->lien : url('/') ); ?>

                    <!-- For old system check if multiple images -->
                    @if($bloc->image_list)
                        @foreach($bloc->image_list as $image)
                            <a style="border: none;padding: 0;margin: 0;" target="_blank" href="<?php echo $lien; ?>">
                                <img style="max-width: 130px;" alt="{{ $bloc->titre }}" src="{{ asset('files/uploads/'.$image) }}" />
                            </a>
                        @endforeach
                    @else
                        <a style="border: none;padding: 0;margin: 0;" target="_blank" href="<?php echo $lien; ?>">
                            <img style="max-width: 130px;" alt="{{ $bloc->titre }}" src="{{ asset('files/uploads/'.$bloc->image) }}" />
                        </a>
                    @endif

                </div>
                <div class="thumbnail mini" ng-show="$flow.files.length">
                    <img flow-img="$flow.files[0]" />
                </div>
                <p class="errorUpload bg-danger text-danger" style="display: none;"></p>
            </td>
        </tr>
        <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr><!-- space -->
    </table>
    <!-- Bloc content-->

    <div class="edit_content_form" id="edit_{{ $bloc->idItem }}">
        <form name="editForm" method="post" action="{{ url('admin/campagne/editContent') }}">
            <?php echo Form::token(); ?>
            <div class="panel panel-orange">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" value="{{ $bloc->titre }}" bind-content ng-model="edit.titre" required name="titre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ajouter un lien sur l'image</label>
                        <input type="text" value="{{ $bloc->lien }}" name="lien" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Texte</label>
                        <textarea required redactor bind-content ng-model="edit.contenu" name="contenu" class="form-control" rows="10">{{ $bloc->contenu }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="btn-group">
                            <input type="hidden" value="{{ $bloc->idItem }}" name="id">
                            <input type="hidden" value="{{ $bloc->type_id }}" name="type_id">
                            <p style="visibility: hidden;height: 1px;margin: 0;" ng-if="$flow.files.length">
                                <input type="text" class="uploadImage" name="image" value="{[{ $flow.files[0].name }]}">
                            </p>
                            <button type="submit" class="btn btn-sm btn-orange">Envoyer</button>
                            <button type="button" data-id="{{ $bloc->idItem }}" class="btn btn-sm btn-default cancelEdit">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

</div>
