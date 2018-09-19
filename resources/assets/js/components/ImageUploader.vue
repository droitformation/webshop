<template>
<div>
    <button type="button" class="btn btn-primary btn-xs" @click="getFiles()" data-toggle="modal" :data-target="'#myModal_' + id">Choisir un fichier</button>
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
                                <div :id="'dropzone_' + id" class="dropzone"></div>
                            </div>
                            <div class="col-md-10">
                               <div class="wrapper-gallery">
                                   <ul v-if="files" class="gallery">
                                       <li v-for="file in files">
                                           <figure class="figure-file-item">
                                               <img v-if="isImage(file)" :src="path + '/' + file" alt="image" />
                                               <img v-if="!isImage(file)" height="105px" src="images/text.svg" alt="image" />
                                           </figure>
                                           <div class="figure-file-item-label">
                                               <p v-if="!isImage(file)">{{ file }}</p>
                                               <button @click="chosenFile(path + '/' + file)" class="btn btn-xs btn-info btn-file-item">Choisir</button>
                                               <button @click="deleteFile(path + '/' + file)" class="btn btn-xs btn-danger">x</button>
                                           </div>
                                       </li>
                                   </ul>
                                   <p class="loading" v-show="loading"><i class="fa fa-spinner fa-spin"></i></p>
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

    <input v-if="chosen && filename && !wrapper" class="file-choosen" :id="id" type="hidden" :name="inputname" v-bind:value="filename">

    <div v-if="chosen && filename && wrapper" class="file-choosen-wrapper">
        <input class="file-choosen" type="hidden" :name="inputname" v-bind:value="filename">
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
    .modal-dialog{
        width:860px;
    }

</style>
<script>

export default {
 props: ['inputname','id','wrapper'],
    data () {
        return {
           path: 'files/uploads',
           files: [],
           chosen: false,
           filename: '',
           loading: false,
        }
    },
    computed: {
    },
    mounted: function ()  {
        this.getFiles();

        var self = this;
        this.$nextTick(function(){

            Dropzone.autoDiscover = false;

            var myDropzone = new Dropzone("#dropzone_" + this.id, {
                url: "admin/upload",
                dictDefaultMessage: " Ajouter un fichier",
                dictRemoveFile: "OK",
                thumbnailWidth: 100,
                thumbnailHeight: 80,
                addRemoveLinks : true
            });

            myDropzone.on('sending', function(file, xhr, formData){
                formData.append('path', self.path);
                formData.append('_token', $("meta[name='_token']").attr('content'));
            });

            myDropzone.on("success", function(file,response) {
                console.log(response.filename);
                self.addFile(response.filename);
            });
        });
    },
    methods: {
         getFiles() {

            this.loading = true;
            var self = this;

            axios.post('/admin/getfiles', { path : this.path }).then(function (response) {
                self.files = response.data.files;
                self.loading = false;
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

            this.$emit('imageChoosen', this.filename)
            $('#myModal_'+this.id).modal('hide');
        },
        addFile: function(file){
            this.files.push(file);
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