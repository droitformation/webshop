<template>
    <div>
        <div class="row">
            <div class="col-md-7" id="StyleNewsletterCreate">
                <!-- Bloc content-->
                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">

                   <tr>
                       <!-- Bloc image gauche-->
                       <td v-if="type == 4" valign="top" align="center" width="160" class="resetMarge">
                           <image-newsletter @imageUploaded="imageUploadedUpdate"></image-newsletter>
                       </td>
                       <td v-if="type == 4" width="25" class="resetMarge"></td><!-- space -->

                       <td valign="top" :width="widthTable" class="resetMarge contentForm">
                           <image-newsletter v-if="(type == 1 || type == 2)" @imageUploaded="imageUploadedUpdate"></image-newsletter>
                           <h2 v-html="create.titre"></h2>
                           <div v-if="hasText" v-html="create.contenu"></div>
                       </td>

                       <!-- Bloc image droite-->
                       <td v-if="type == 3" width="25" class="resetMarge"></td><!-- space -->
                       <td v-if="type == 3" valign="top" align="center" width="160" class="resetMarge">
                           <image-newsletter @imageUploaded="imageUploadedUpdate"></image-newsletter>
                       </td>
                   </tr>

                </table>
                <!-- Bloc content-->
            </div>
            <div class="col-md-5">
                <form name="blocForm" method="post" :action="url"><input name="_token" :value="_token" type="hidden">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <h3>{{ title }}</h3>
                            <div class="form-group">
                                <label>Titre</label>
                                <input v-model="create.titre" type="text" required name="titre" class="form-control">
                            </div>
                            <div class="form-group" v-if="hasText">
                                <label>Texte</label>
                                <textarea v-model="create.contenu" required name="contenu" :class="'form-control redactorBuild_' + type" rows="10">{{ create.contenu }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="btn-group">
                                    <input type="hidden" v-if="uploadImage" :value="uploadImage" name="image">
                                    <input type="hidden" :value="type" name="type_id">
                                    <input type="hidden" :value="campagne.id" name="campagne">
                                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                                    <button type="button" class="btn btn-sm btn-default cancelCreate">Annuler</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style>
    .upload-btn-wrapper {
      position: relative;
      overflow: hidden;
      display: inline-block;
    }

    .upload-btn-wrapper input[type=file] {
      font-size: 100px;
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
    }
</style>
<script>
    import ImageNewsletter from './partials/ImageNewsletter.vue';

    export default{

        props: ['type','campagne','_token','url','title'],
        components:{
            'image-newsletter' : ImageNewsletter,
        },
        data(){
            return{
                create: {
                   titre : '',
                   contenu : ''
                },
                image:null,
                uploadImage:null
            }
        },
        computed: {
            widthTable: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 6) ? '560' : '375';
            },
            hasText: function () {
                return (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            }
        },
        components:{
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            initialize : function(){

                this.$nextTick(function(){
                    var self = this;

                    $('.redactorBuild_' + self.type).redactor({
                        minHeight: 50,
                        maxHeight: 270,
                        lang: 'fr',
                        plugins: ['imagemanager','filemanager'],
                        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                        fileManagerJson: 'admin/fileJson?_token=' +   $('meta[name="_token"]').attr('content'),
                        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
                        plugins: ['iconic'],
                        buttons  : ['html','formatting','bold','italic','link','image','file','|','unorderedlist','orderedlist'],
                        blurCallback:function(e){
                            var text = this.code.get();
                            self.create.contenu = this.code.get();
                        }
                    });

                });
            },
            imageUploadedUpdate(value){
                console.log(value);
                this.uploadImage = value;
            }
        }
    }
</script>
