<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal">
    <div class="modal-dialog modal-dialog-big" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Choisir un fichier</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-2">
                        <div id="treeMenu">

                            <div class="tree">
                                <div id="fileManagerTree"></div>
                            </div>

                            <div id="dropzone" class="dropzone"></div>
                            <p class="dropmessage"><i>Les fichiers sont téléchargé dans le dossier en cours, sinon si aucun n'est choisi le dossier par défault sera uploads</i></p>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div id="fileManager" data-path="files/uploads"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
