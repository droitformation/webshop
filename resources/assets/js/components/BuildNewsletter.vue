<template>
    <div>
        <div class="row">
            <div class="col-md-7" id="StyleNewsletterCreate">

                <div class="row" style="margin-bottom:10px">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <div v-if="content && mode == 'edit'" style="margin-bottom:5px;">
                            <button v-if="model && !isEdit" @click="editMode(content)" class="btn btn-xs btn-warning pull-left">éditer</button>
                            <form method="post" :action="action" v-if="model && !isEdit" class="pull-right">
                                <input name="_token" :value="_token" type="hidden">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" :value="model.id" />
                                <input type="hidden" :value="campagne.id" name="campagne_id">
                                <button type="submit" class="btn btn-xs btn-danger deleteNewsAction" :data-id="model.id" :data-action="model.titre">x</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bloc content-->
                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">

                   <tr>
                       <!-- Bloc image gauche-->
                       <td v-if="type == 4" valign="top" align="center" width="160" class="resetMarge">
                           <image-newsletter :visible="isImage" :mode="mode" :type="type" @imageUploaded="imageUploadedUpdate" :model="model"></image-newsletter>
                       </td>
                       <td v-if="type == 4" width="25" class="resetMarge"></td><!-- space -->

                       <td valign="top" :width="widthTable" class="resetMarge contentForm">
                           <image-newsletter :visible="isImage" :mode="mode" :type="type" v-if="(type == 1 || type == 2)" :model="model" @imageUploaded="imageUploadedUpdate"></image-newsletter>
                           <h3 :style="align" v-html="content.titre"></h3>
                           <div v-if="hasText" v-html="content.contenu"></div>
                       </td>

                       <!-- Bloc image droite-->
                       <td v-if="type == 3 || type == 10" width="25" class="resetMarge"></td><!-- space -->
                       <td v-if="type == 3 || type == 10" valign="top" align="center" width="160" class="resetMarge">
                           <image-newsletter :visible="isImage" :mode="mode" :type="type" v-if="type == 3" @imageUploaded="imageUploadedUpdate" :model="model" ></image-newsletter>
                           <img v-if="type == 10 || categorie" :src="imgcategorie" class="img-responsive">
                       </td>
                   </tr>
                </table>
                <!-- Bloc content-->
            </div>
            <div class="col-md-5 edit_bloc_form" v-show="isEdit || mode == 'create'">
                <form name="blocForm newsletterForm" method="post" :action="action">

                    <input name="_token" :value="_token" type="hidden">
                    <input v-if="mode == 'edit'" type="hidden" name="_method" value="PUT">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <h3>{{ title }}</h3>

                            <div v-if="type == 10">
                                <select class="form-control form-required required" v-model="categorie" name="id">
                                    <option v-if="!categorie" :value="null" disabled>Sélectionner catégorie</option>
                                    <option v-for="categorie in categories" v-bind:value="categorie">{{ categorie.title }}</option>
                                </select><br/>
                            </div>

                            <div class="form-group">
                                <label>Titre</label>
                                <input v-model="content.titre" type="text" required name="titre" class="form-control">
                            </div>
                            <div class="form-group" v-if="hasText">
                                <label>Texte</label>
                                <textarea v-model="content.contenu" required name="contenu" :class="'form-control redactorBuild_' + hash" rows="10">{{ content.contenu }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="btn-group">
                                    <input type="hidden" v-if="uploadImage" :value="uploadImage" name="image">
                                    <input type="hidden" v-if="categorie" :value="categorie.image" name="image">
                                    <input type="hidden" :value="type" name="type_id">
                                    <input type="hidden" :value="campagne.id" name="campagne">
                                    <input v-if="model" type="hidden" name="id" :value="model.id" />
                                    <input v-if="categorie" type="hidden" name="categorie_id" :value="categorie.id" />
                                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                                    <button type="button" @submit.prevent @click="close" class="btn btn-sm btn-default cancelCreate">Annuler</button>
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
    #StyleNewsletterCreate{
        margin-top:5px;
    }
    .edit_bloc_form::before{
        color: #85c744;
        content: "◄";
        display: block;
        font-size: 14px;
        font-weight: bold;
        height: 10px;
        left: -2px;
        position: absolute;
        top: 0px;
        width: 5px;
    }
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
            cursor:pointer;
    }
    .margeUp{
        margin-top:5px;
    }
</style>
<script>
    import ImageNewsletter from './partials/ImageNewsletter.vue';

    export default{

        props: ['type','campagne','_token','url','title','model','site','mode'],
        components:{
            'image-newsletter' : ImageNewsletter,
        },
        data(){
            return{
                create: {
                   titre : '',
                   contenu : ''
                },
                content: {},
                image:null,
                uploadImage:null,
                categories: [],
                categorie: null,
                isEdit: false,
                isImage:null,
                hash: null
            }
        },
        computed: {
            widthTable: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 6) ? '560' : '375';
            },
            hasText: function () {
                return (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            align: function () {
                return (this.type == 1) ? 'text-align:center;' : 'text-align:left;';
            },
            imgcategorie:function(){
                if(this.model){
                    return  this.model.model.categorie.path
                }
                if(this.categorie){
                    return this.categorie.path;
                }

                return '';
            },
            action:function(){
                if(this.mode == 'edit'){ return this.url + '/' + this.content.id; }
                if(this.mode == 'create'){ return this.url; }
            },
        },
        components:{
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            makeHash(){
                this.hash = Math.random().toString(36).substring(7);
            },
            initialize : function(){

                this.makeHash();

                if(this.type == 10){
                    this.getCategories();
                    this.categorie = this.model ? this.model.categorie : null;
                }

                this.content = this.model ? this.model : this.create;
                this.isEdit  = !this.content ? true : false;

                if(!this.model){
                   this.isImage = true;
                }

                this.$nextTick(function(){
                    var self = this;

                    $('.redactorBuild_' + self.hash).redactor({
                        minHeight: '180px',
                        maxHeight: '370px',
                        removeEmpty : [ 'strong' , 'em' , 'span' , 'p' ],
                        lang: 'fr',
                        plugins: ['imagemanager','filemanager','fontsize','fontcolor','alignment'],
                        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
                        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
                        imageResizable: true,
                        imagePosition: true,
                        formatting: ['h1', 'h2','h3','p', 'blockquote'],
                        callbacks: {
                            focus:function(e){
                                var text = this.source.getCode();
                                self.content.contenu = this.source.getCode();
                            },
                            enter: function(e)
                            {
                               return !(window.event && window.event.keyCode == 13 && window.event.keyCode == 46);
                            }
                        }

                    });

                });
            },
            getCategories: function() {
                var self = this;
                axios.get('admin/ajax/categories/' + self.site).then(function (response) {
                     self.categories = response.data;
                     self.categorie = self.model ? self.content.model.categorie : null;
                }).catch(function (error) { console.log(error);});
            },
            imageUploadedUpdate(value){
                this.uploadImage = value;
            },
            editMode(model){
                this.isEdit = true;
                this.isImage = true;
            },
            close(){
                this.isEdit = false;
                this.isImage = false;
                this.initialize();
                if(this.mode == 'create'){
                    this.model = null;
                    this.$emit('cancel', this.cancel);
                }
            },
            deleteContent(model){
                this.$emit('deleteContent', model);
            }
        }
    }
</script>
