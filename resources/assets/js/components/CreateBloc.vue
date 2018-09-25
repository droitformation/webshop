<template>
    <div>
        <div class="row">
            <div class="col-md-7" id="StyleNewsletterCreate">

                <arret v-if="type == 5 && model" :newsletter="newsletter" :arret="model"></arret>
                <text-content v-if="hasTitle" :newbloc="newbloc" :categorie="model" :type="type" @imageUploaded="imageUploadedUpdate"></text-content>
                <model-content v-if="type != 5 && model" :type="type" :model="model"></model-content>

            </div>
            <div class="col-md-5 edit_bloc_form">
                <form name="blocForm newsletterForm" method="post" :action="action">

                    <input name="_token" :value="_token" type="hidden">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <h3>{{ title }}</h3>

                            <div v-if="type == 10 || type == 5 || type == 9 || type == 8 || type == 7">
                                <select class="form-control form-required required" @change="getSingle(selected)" v-model="selected" name="model_id">
                                    <option v-if="!selected" :value="null" disabled>Sélectionner</option>
                                    <option v-for="model in models" v-bind:value="model.id">{{ model.title }}</option>
                                </select><br/>
                            </div>

                            <div class="form-group" v-if="hasTitle">
                                <label>Titre</label>
                                <input v-model="newbloc.titre" type="text" required name="titre" class="form-control">
                            </div>
                            <div class="form-group" v-if="hasText">
                                <label>Texte</label>
                                <textarea v-model="newbloc.contenu" required name="contenu" :class="'form-control redactorBuild_' + hash" rows="10">{{ newbloc.contenu }}</textarea>
                            </div>

                            <div v-if="type == 7" class="row drag">
                                <div class="col-md-6">
                                    <draggable v-model="arrets" class="dragArea" :options="{group:'arret'}">
                                        <div v-for="element in arrets" :key="element.id">{{ element.reference }}</div>
                                    </draggable>
                                </div>
                                <div class="col-md-6">
                                    <draggable v-model="choosen" class="dragArea" :options="{group:'arret'}">
                                        <div v-for="element in choosen" :key="element.id">{{ element.reference }}</div>
                                    </draggable>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="btn-group">
                                    <input type="hidden" v-if="uploadImage" :value="uploadImage" name="image">
                                    <input type="hidden" v-if="model && path == 'categorie'" :value="model.image" name="image">
                                    <input type="hidden" :value="type" name="type_id">
                                    <input type="hidden" :value="campagne.id" name="campagne">

                                    <input v-if="model" type="hidden" :name="path + '_id'" :value="model.id" />
                                    <input v-for="chose in choosen" type="hidden" name="arrets[]" :value="chose.id" />

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

    .dragArea {
        height: 300px;
        margin: 0 0 20px 0;
        padding: 3px;
        overflow: scroll;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;
    }

    .dragArea div {
        width: 100%;
        height: auto;
        line-height: 18px;
        padding: 5px;
        cursor: pointer;
        box-shadow: 0px 0px 2px 0px rgba(222, 222, 222, 1.0);
    }

    .sortable-ghost {
        color: #EAEAEA;
        background-color: #EAEAEA;
        border: 1px dashed #aaa;
    }
    .sortable-chosen:not(.sortable-ghost) {
        color: #224466;
        background-color: #2299ff;
    }
    .sortable-drag {
        color: #449922;
        background-color: #44ff33;
    }
</style>
<script>
    import ImageNewsletter from './partials/ImageNewsletter.vue';
    import Arret from './blocs/Arret.vue';
    import TextContent from './blocs/TextContent.vue';
    import ModelContent from './blocs/ModelContent.vue';
    import draggable from 'vuedraggable';

    export default{

        props: ['type','campagne','newsletter','_token','url','title','site'],
        components:{
            'image-newsletter' : ImageNewsletter,
            'arret' : Arret,
            'text-content' : TextContent,
            'model-content' : ModelContent,
            draggable,
        },
        data(){
            return{
                newbloc: {
                   titre : '',
                   contenu : '',
                   image : null,
                },
                uploadImage:null,
                models: [],
                arrets:[],
                choosen: [],
                selected:null,
                model:null,
                hash: null
            }
        },
        watch: {
            // whenever question changes, this function will run
            type: function (newType, oldType) {
                this.initialize();
            },
            selected: function (newSelected, oldSelected) {
                this.getSingle();

                if(this.type == 7){
                    this.getArrets();
                }
            },
        },
        computed: {
            widthTable: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 6) ? '560' : '375';
            },
            hasText: function () {
                return (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            hasTitle: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            align: function () {
                return (this.type == 1) ? 'text-align:center;' : 'text-align:left;';
            },
            path: function () {
                if(this.type == 5){return 'arret';}
                if(this.type == 7 || this.type == 10){return 'categorie';}
                if(this.type == 8){ return 'product';}
                if(this.type == 9){ return 'colloque';}

                return null;
            },
            action:function(){
                return this.url;
            },
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            makeHash(){
                this.hash = Math.random().toString(36).substring(7);
            },
            initialize : function(){

                // remove all content
                this.newbloc.titre = '';
                this.newbloc.contenu = '';
                this.model = null;
                this.arrets = [];

                // initialize textarea and get list of models
                this.makeHash();
                this.getModels();

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
                            blur:function(e){
                                self.newbloc.contenu = this.source.getCode();
                            },
                            enter: function(e){
                               return !(window.event && window.event.keyCode == 13 && window.event.keyCode == 46);
                            }
                        }
                    });
                });
            },
            getModels: function() {
                var self = this;
                if(this.path){
                     axios.get('admin/ajax/list/'+ this.path +'/' + self.site).then(function (response) {
                         self.models = response.data;
                     }).catch(function (error) { console.log(error);});
                }
                else{
                    this.models = [];
                }
            },
            getArrets: function() {
                var self = this;
                if(this.selected){
                     axios.get('admin/ajax/categoriearrets/' + this.selected).then(function (response) {
                         self.arrets = response.data;
                     }).catch(function (error) { console.log(error);});
                }
                else{
                    this.arrets = [];
                }
            },
            getSingle: function() {
                var self = this;
                if(this.path){
                     axios.get('admin/ajax/single/'+ this.path +'/' + self.selected).then(function (response) {
                         self.model = response.data;
                     }).catch(function (error) { console.log(error);});
                }
                else{
                    this.model = null;
                }
            },
            imageUploadedUpdate(value){
                this.uploadImage = value;
            },
            close(){
                this.isEdit = false;
                this.isImage = false;
                this.initialize();
                this.$emit('cancel', this.cancel);
            }
        }
    }
</script>
