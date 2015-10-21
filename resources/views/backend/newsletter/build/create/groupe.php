<form class="row form-horizontal" name="arretForm" method="post" action="<?php echo url('admin/campagne/process'); ?>">
    <?php echo csrf_field(); ?>

    <div class="col-md-7" id="StyleNewsletterCreate">

    </div>
    <div class="col-md-5 create_content_form">
        <div class="panel panel-success">
            <div class="panel-body">

                <div class="form-group">
                    <label for="categories" class="control-label">Catégorie</label>
                    <div class="row">
                        <div class="col-sm-8">

                            <select name="categorie_id" class="form-control">
                                <?php
                                if(!empty($categories))
                                {
                                    foreach($categories as $idcat => $categorie)
                                    {
                                        $haystack = ['CO','CCT','LEg','LTr','Fonction publique'];

                                        if(in_array($categorie,$haystack)){
                                            $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $imgcategories[$idcat]);
                                            echo '<option value="'.$idcat.'">'.$categorie.'<small> - '.$withoutExt.'</li>';
                                        }
                                        else{
                                            echo '<option value="'.$idcat.'">'.$categorie.'</small></li>';
                                        }
                                    }
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Sélectionner des arrêts</label>

                    <div ng-controller="MultiSelectionController as selectarret">

                        <div class="listArrets forArrets" ng-init="typeItem='arrets'">
                            <div ng-repeat="(listName, list) in selectarret.models.lists">
                                <ul class="list-arrets" dnd-list="list">
                                    <li ng-repeat="item in list"
                                        dnd-draggable="item"
                                        dnd-moved="list.splice($index, 1); logEvent('Container moved', event); selectarret.dropped(item)"
                                        dnd-effect-allowed="move"
                                        dnd-selected="models.selected = item"
                                        ng-class="{'selected': models.selected === item}" >
                                        {[{ item.reference }]}
                                        <input type="hidden" name="arrets[]" ng-if="item.isSelected" value="{[{ item.itemId }]}" />
                                    </li>
                                </ul>
                            </div>
                            <div view-source="simple"></div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="btn-group">
                        <input type="hidden" value="<?php echo $bloc->id; ?>" name="type_id">
                        <input type="hidden" value="<?php echo $infos->id; ?>" name="campagne">
                        <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                        <button type="button" class="btn btn-sm btn-default cancelCreate">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>