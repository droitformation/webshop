<template>
<div>
    <div id="bs-modal">
        <!-- MODAL -->
        <div class="modal fade" :id="'myModal_' + id" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header manager-modal">
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

                                    <div v-if="files" v-for="(row, index) in files">
                                        <p class="mt-10"><strong>{{ index }}</strong></p>
                                        <div class="gallery-wrapper">
                                            <div class="file-item" v-for="file in row">
                                                <button @click="deleteFile(file)" class="btn btn-xs btn-danger">x</button>
                                                <img v-if="isImage(file)" @click="chosenFile(file)" :src="file" alt="image" />
                                                <img v-if="!isImage(file)" @click="chosenFile(file)" src="images/text.svg" alt="image" />
                                                <p>{{ nom(file) }}</p>
                                            </div>
                                        </div>
                                    </div>

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
        <img v-if="isImage(filename)" class="file-choosen file-image thumbnail" :src="root + filename" alt="image" />
        <a v-if="!isImage(filename)" target="_blank" class="file-choosen" :href="root + filename">{{ filename }}</a>
        <button @click="removeFile()" class="btn btn-xs btn-danger">x</button>
    </div>

</div>

</template>

<style scoped>

    #fileManager {
        overflow-y: auto;
        max-height: 70vh;
    }
   .loading{
        width:50px;
        margin:40px auto;
        font-size:30px;
    }
    .gallery-wrapper{
        display: flex;
        flex-direction: row;
        justify-content: start;
        flex-wrap: wrap;
    }
   .file-item {
       width: 120px;
       height: 135px;
       position: relative;
       list-style: none;
       margin: 5px;
       padding: 20px 5px 5px 5px;
       background: #fff;
       display: flex;
       flex-direction: column;
       align-items: center;
       justify-content: space-between;
       border: 5px solid transparent;
       overflow-x: hidden;
   }
   .file-item img {
       max-width: 120px;
       max-height: 90px;
       background-position: center;
       background-repeat: no-repeat;
       background-size: cover;
       cursor: pointer;
   }
   .file-item p{
       margin: 5px 0 0 0;
       padding: 0;
       text-align: center;
       font-size: 12px;
       line-height: 12px;
       word-wrap: anywhere;
   }
   .file-item button {
       position: absolute;
       top: 0;
       right: 0;
       padding: 0 5px 2px 5px;
   }
    .tree ul button {
        background-color: #428bca;
        border: 1px solid #357ebd;
        color: #ffffff;
        cursor: pointer;
        display: block;
        line-height: 16px;
        padding: 3px 0 3px 10px;
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .modal-header.manager-modal {
        padding:10px  15px;
        border-bottom: 1px solid #e5e5e5;
        min-height: 15px;
    }

    .modal-dialog {
        margin-top: 95px;
    }
</style>
<script>

export default {
 props: ['name','thumbs', 'input','id','root'],
    data () {

        return {
           directories:[],
           path: 'files/uploads',
           url: location.protocol + "//" + location.host+"/",
           files: [],
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
        },
        nom(fullPath){
            return fullPath.replace(/^.*[\\\/]/, '')
        }
    }
}
</script>