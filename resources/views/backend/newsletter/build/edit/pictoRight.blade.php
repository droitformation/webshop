<div class="edit_content" ng-controller="EditCategorieController as edit">

    <!-- Bloc content-->
    <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
        <tr bgcolor="ffffff">
            <td colspan="3" height="35">
                <div class="pull-right btn-group btn-group-xs">
                    <button class="btn btn-warning editContent" ng-click="edit.editContent({{ $bloc->id }})" data-id="{{ $bloc->id }}"type="button">éditer</button>
                    <button class="btn btn-danger deleteActionNewsletter deleteContentBloc" data-id="{{ $bloc->id }}" data-action="{{ $bloc->titre }}" type="button">&nbsp;×&nbsp;</button>
                </div>
            </td>
        </tr><!-- space -->
        <tr>
            <td valign="top" width="375" class="resetMarge contentForm">
                <h2 ng-bind="edit.titre">{{ $bloc->titre }}</h2>
                <div ng-bind-html="edit.contenu">{!! $bloc->contenu  !!}</div>
            </td>
            <td width="25" class="resetMarge"></td><!-- space -->
            <td valign="top" align="center" width="160" class="resetMarge">
                <a target="_blank" href="#">
                    <img ng-hide="selected" width="130" border="0" alt="{{ $bloc->title }}" src="{{ secure_asset(config('newsletter.path.categorie').$bloc->image) }}">
                    <img ng-show="selected" width="130" border="0" alt="{{ $bloc->title }}" ng-src="{{ secure_asset(config('newsletter.path.categorie').'{[{ selected.image }]}') }}">
                </a>
            </td>
        </tr>
        <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr><!-- space -->
    </table>
    <!-- Bloc content-->

    <div class="edit_content_form" id="edit_{{ $bloc->id }}">
        <form name="editForm" method="post" action="{{ url('build/content/'.$bloc->id) }}">{!! csrf_field() !!}
            <input type="hidden" name="_method" value="PUT">
            <div class="panel panel-orange">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Sélectionner le picto</label>
                        <select class="form-control" name="categorie_id" ng-change="edit.changed()" ng-model="selected" ng-options="categorie.title for categorie in edit.categories track by categorie.id">
                            <option value="">Choisir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" value="{{ $bloc->titre }}" bind-content ng-model="edit.titre" required name="titre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Texte</label>
                        <textarea redactor bind-content ng-model="edit.contenu" name="contenu" class="form-control" rows="10">{!! $bloc->contenu !!}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="btn-group">
                            <input type="hidden" value="{{ $bloc->id }}" name="id">
                            <input type="hidden" value="{{ $bloc->type_id }}" name="type_id">
                            <input ng-if="selected" type="hidden" value="{[{ selected.image }]}" name="image">
                            <button type="submit" class="btn btn-sm btn-warning">Envoyer</button>
                            <button type="button" data-id="{{ $bloc->id }}" class="btn btn-sm btn-default cancelEdit">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

</div>
