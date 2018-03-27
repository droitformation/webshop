<form ng-controller="SelectCategorieController as select" class="row form-horizontal" name="arretForm" method="post" action="{{ url('build/content') }}">
    {{ csrf_field() }}
    <div class="col-md-7" id="StyleNewsletterCreate">
        <!-- Bloc content-->
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
            <tr>
                <td valign="top" width="375" class="resetMarge contentForm">
                    <h2 ng-bind="create.titre"></h2>
                    <div ng-bind-html="create.contenu | to_trusted"></div>
                </td>
                <td width="25" class="resetMarge"></td><!-- space -->
                <td align="center" valign="top" width="160" class="resetMarge">
                    <a target="_blank" href="#">
                        <img ng-show="selected.image" width="130" border="0" alt="{[{ selected.title }]}" ng-src="{{ secure_asset(config('newsletter.path.categorie').'{[{ selected.image }]}') }}">
                    </a>
                </td>
            </tr>
        </table>
        <!-- Bloc content-->
    </div>
    <div class="col-md-5 create_content_form">

        <div class="panel panel-success">
            <div class="panel-body">

                <div class="form-group">
                    <label>Sélectionner le picto</label>
                    <select class="form-control" name="categorie_id" ng-change="select.changed()" ng-model="selected" ng-options="categorie.title for categorie in select.categories track by categorie.id">
                        <option value="">Choisir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Titre</label>
                    <input bind-content ng-model="create.titre" type="text" value="" required name="titre" class="form-control">
                </div>
                <div class="form-group">
                    <label>Texte</label>
                    <textarea bind-content redactor ng-model="create.contenu" required name="contenu" class="form-control" rows="10"></textarea>
                </div>

                <div class="btn-group" style="margin-top: 10px;">
                    <input type="hidden" value="{[{ selected.image }]}" name="image">
                    <input type="hidden" value="{{ $bloc->id }}" name="type_id">
                    <input type="hidden" value="{{ $campagne->id }}" name="campagne">
                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                    <button type="button" class="btn btn-sm btn-default cancelCreate">Annuler</button>
                </div>
            </div>
        </div>

    </div>
</form>