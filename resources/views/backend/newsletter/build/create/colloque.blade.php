<form ng-controller="SelectColloqueController as select" class="row form-horizontal" name="colloqueForm" method="post" action="{{ url('build/content') }}">
    {{ csrf_field() }}
    <div class="col-md-7" id="StyleNewsletterCreate">
        <!-- Bloc content-->
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
            <tr>
                <td valign="top" width="375" class="resetMarge contentForm">
                    <div ng-show="select.colloque" ng-model="select.colloque">
                        <h3>{[{ select.colloque.titre }]}</h3>
                        <p class="abstract">{[{ select.colloque.date }]}</p>
                        <p><strong>Lieu: </strong><cite>{[{ select.colloque.location }]}</cite></p>

                        <h4 ng-show="select.colloque.themes.length">Thèmes principaux</h4>
                        <div ng-bind-html="select.colloque.themes" ng-show="select.colloque.themes.length" style="text-align: left;" class="themes"></div>

                    </div>
                </td>
                <td valign="top" width="25" class="resetMarge"></td>
                <td valign="top" width="160" class="resetMarge">
                    <a href="{[{ select.colloque.link }]}">
                        <img class="media-object" style="max-width: 150px;" src="{[{ select.colloque.illustration }]}" />
                    </a>
                </td>
            </tr>
        </table>
        <!-- Bloc content-->

    </div>
    <div class="col-md-5 create_content_form">

        <div class="panel panel-success">
            <div class="panel-body">
                <label>Sélectionner le colloque</label>
                <select class="form-control" name="colloque_id" ng-change="select.changed()" ng-model="selected" ng-options="colloque.droptitle for colloque in select.colloques track by colloque.id"></select>

                <div class="btn-group" style="margin-top: 10px;">

                    <input type="hidden" value="{{ $bloc->id }}" name="type_id">
                    <input type="hidden" value="{{ $campagne->id }}" name="campagne">
                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                    <button type="button" class="btn btn-sm btn-default cancelCreate">Annuler</button>
                </div>
            </div>
        </div>

    </div>
</form>