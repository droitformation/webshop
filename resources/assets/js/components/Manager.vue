<template>
<div>
    <div id="bs-modal">
        <!-- MODAL -->
        <div class="modal fade" :id="'myModal_' + id" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
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
                                        <div id="fileManagerTree">
                                            <ul>
                                                <li v-bind:class="{active: isActive(index)}" v-for="(directorie,index) in directories">
                                                    <button type="button" class="node" v-on:click.stop="chosenFolder('files/' + index)"><i class="fa fa-folder-o"></i> &nbsp;{{ index }}</button>
                                                    <ul>
                                                        <li v-bind:class="{active: isActive(second)}" v-for="(folder,second) in directorie">
                                                            <button type="button" class="node" v-on:click.stop="chosenFolder('files/' + index + '/' + second)"><i class="fa fa-folder-o"></i> &nbsp;{{ second }}</button>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div :id="'dropzone_' + id" class="dropzone"></div>
                                    <p class="dropmessage"><i>Les fichiers sont téléchargé dans le dossier en cours, sinon si aucun n'est choisi le dossier par défault sera uploads</i></p>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <p class="loading" v-show="loading"><i class="fa fa-spinner fa-spin"></i></p>
                                <div v-show="!loading" id="fileManager" data-path="files/uploads">
                                    <p v-if="!files">Aucun fichier à ce niveau</p>
                                    <ul v-if="files" id="gallery">
                                        <li v-for="file in files" class="file-item">
                                            <button @click="deleteFile(path + '/' + file)" class="btn btn-xs btn-danger">x</button>

                                            <img v-if="isImage(file)" @click="chosenFile(path + '/' + file)" :src="path + displayPath + '/' + file" alt="image" />
                                            <img v-if="!isImage(file)" @click="chosenFile(path + '/' + file)" src="images/text.svg" alt="image" />
                                            <p v-if="!isImage(file)">{{ file }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="chosen && filename" class="file-choosen-wrapper">
        <input class="file-choosen" type="hidden" :name="name" v-bind:value="filename">
        <img v-if="isImage(filename)" class="file-choosen file-image thumbnail" :src="filename" alt="image" />
        <a v-if="!isImage(filename)" target="_blank" class="file-choosen" :href="filename">{{ filename }}</a>
        <button @click="removeFile()" class="btn btn-xs btn-danger">x</button>
    </div>

</div>

</template>

<style>
   .loading{
        width:50px;
        margin:40px auto;
        font-size:30px;
    }
</style>
<script>

export default {
 props: ['name','thumbs', 'input','id'],
    data () {
        return {
           directories:[],
           path: 'files/uploads',
           files: null,
           chosen: false,
           filename: '',
           directory:'',
           loading: false
        }
    },
    computed: {
        displayPath: function (path) {
           return this.thumbs.indexOf(this.directory) === 0 ? '/thumbs' : '';
        }
    },
    mounted: function ()  {
        this.getDirectories();

        var self = this;
        this.$nextTick(function(){

            var myDropzone = new Dropzone("#dropzone_" + this.id, {
                url: "admin/upload",
                dictDefaultMessage: " Ajouter un fichier",
                dictRemoveFile: "Enlever",
                thumbnailWidth: 100,
                thumbnailHeight: 80,
                addRemoveLinks : true
            });

            myDropzone.on('sending', function(file, xhr, formData){
                formData.append('path', self.path);
                formData.append('_token', $("meta[name='_token']").attr('content'));
            });

            myDropzone.on("success", function(file) {
                self.addFile(file);
            });
        });
    },
    methods: {
        getDirectories: function(){

            var self = this;
            axios.get('/admin/gettree').then(function (response) {
                 self.directories = response.data.directories;
            }).catch(function (error) { console.log(error);});
        },
        chosenFolder: function(path){

            this.loading = true;
            this.directory = path.replace("files/", "");
            this.files = null

            var self = this;
            axios.post('/admin/getfiles', { path : path }).then(function (response) {

                self.files = response.data.files;
                self.path  = path;

                self.$nextTick(function(){
                    self.loading = false;
                });

            }).catch(function (error) { console.log(error);});
        },
        deleteFile: function(path){

            var self = this;
            axios.post('/admin/files/delete', { path : path }).then(function (response) {
                var answer = confirm('Voulez-vous vraiment supprimer ' + path + ' ?');
                if (answer){ self.files.splice( self.files.indexOf(path), 1 );}
            }).catch(function (error) { console.log(error);});
        },
        chosenFile: function(path){
            this.filename = path;
            this.chosen   = true;

            $('#myModal_'+this.id).modal('hide');
        },
        addFile: function(file){
            this.files.push(file.name);
        },
        removeFile: function(){
            this.filename = null;
            this.chosen   = false;
        },
        isActive: function(path){
            return this.directory === path ? true : false;
        },
        isImage: function(filename){

            var get_ext = filename.split('.').reverse();
            var exts    = ['jpg','jpeg','png','gif'];

            return ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ) ? true : false;
        }
    }
}
</script>