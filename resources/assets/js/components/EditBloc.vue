<template>
    <div>
        <div v-show="activ" class="pull-right" style="margin-top:5px;">
            <form method="post" :action="action" class="pull-right">
                <input name="_token" :value="_token" type="hidden">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="id" :value="bloc.id" />
                <input type="hidden" :value="campagne.id" name="campagne_id">
                <button type="submit" class="btn btn-xs btn-danger deleteNewsAction" :data-id="bloc.id" :data-action="bloc.titre">x</button>
            </form>
            <button :id="'btn'+bloc_id" class="btn btn-info btn-xs" type="button" @click="makeVisible">editer</button>
        </div>

        <arret v-if="type == 5 && model && visible" class="paddingUp" :newsletter="newsletter" :arret="model"></arret>
        <text-content v-if="hasTitle && visible" class="paddingUp" :newbloc="newbloc" :categorie="model" :type="type" @imageUploaded="imageUploadedUpdate"></text-content>
        <model-content v-if="!hasTitle && model && visible && type != 5 && type != 10  && type != 7" :color="color" class="paddingUp" :model="model" :type="type"></model-content>

        <div class="wrapper-bloc-edit" v-if="visible">
            <div class="edit_bloc_form">
                <form name="blocForm newsletterForm" method="post" :action="action">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" :value="_token" type="hidden">
                    <div class="panel panel-warning">
                        <div class="panel-body">

                            <div v-if="type == 10 || type == 5 || type == 9 || type == 8 || type == 7">
                                <select :disabled="type == 7" class="form-control form-required required" @change="getSingle(selected)" v-model="selected" name="model_id">
                                    <option v-if="!selected" :value="null" disabled>Sélectionner</option>
                                    <option v-for="model in models" v-bind:value="model.id">{{ model.title }}</option>
                                </select><br/>
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

                            <div class="form-group" v-if="hasTitle">
                                <label>Titre</label>
                                <input v-model="newbloc.titre" type="text" required name="titre" class="form-control">
                            </div>
                            <div class="form-group" v-if="hasImage">
                                <label>Ajouter un lien sur l'image</label>
                                <input v-model="newbloc.lien" type="text" value="" name="lien" class="form-control">
                            </div>
                            <div class="form-group" v-if="hasText">
                                <label>Texte</label>
                                <textarea v-model="newbloc.contenu" required name="contenu" :class="'form-control redactorBuild_' + hash" rows="10">{{ newbloc.contenu }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="btn-group">
                                    <input type="hidden" v-if="uploadImage" :value="uploadImage" name="image">
                                    <input type="hidden" :value="bloc.id" name="id">
                                    <input type="hidden" :value="type" name="type_id">
                                    <input type="hidden" :value="campagne.id" name="campagne">
                                    <input type="hidden" :value="bloc.groupe_id" name="groupe_id">
                                    <input v-if="model" type="hidden" :name="path + '_id'" :value="selected" />
                                    <input type="hidden" v-if="type == 10" :value="model.image" name="image">
                                    <input v-for="chose in choosen" type="hidden" name="arrets[]" :value="chose.id" />
                                    <button type="submit" class="btn btn-sm btn-warning">Envoyer</button>
                                    <button type="button" @submit.prevent @click="makeVisible" class="btn btn-sm btn-default cancelCreate">Annuler</button>
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
.wrapper-bloc-edit{
    position:absolute;
    top:0;
    left:600px;
}
.edit_bloc_form{
    width: 640px;
}
.edit_bloc_form::before{
        color: #f1c40f;
        content: "◄";
        display: block;
        font-size: 14px;
        font-weight: bold;
        height: 10px;
        left: -12px;
        position: absolute;
        top: 0px;
        width: 5px;
    }
    .paddingUp{
        padding-top:55px;
        padding-bottom:10px;
        border-bottom:1px solid #f5f5f5;
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

    import Arret from './blocs/Arret.vue';
    import TextContent from './blocs/TextContent.vue';
    import ModelContent from './blocs/ModelContent.vue';
    import draggable from 'vuedraggable';

    export default{

        props: ['bloc','campagne','newsletter','type','_token','url','site'],
        components:{
            'arret' : Arret,
            'text-content' : TextContent,
            'model-content' : ModelContent,
             draggable,
        },
        data(){
            return{
                newbloc:{
                    titre: this.bloc.titre,
                    contenu: this.bloc.contenu,
                    filename: this.bloc.image,
                    path: this.bloc.path,
                    lien: this.bloc.lien,
                },
                visible: false,
                activ:true,
                models: [],
                selected: null,
                hash: null,
                model:null,
                uploadImage:null,
                arrets: this.bloc.model && this.bloc.model.listearrets ? this.bloc.model.listearrets :[] ,
                choosen: this.bloc.model && this.bloc.model.choosen ? this.bloc.model.choosen : [] ,
            }
        },
        watch: {},
        computed: {
            bloc_id(){
                return 'bloc_' + this.bloc.id;
            },
            action:function(){
                return this.url + '/' + this.bloc.id;;
            },
            path: function () {
                if(this.type == 5){return 'arret';}
                if(this.type == 7 || this.type == 10){return 'categorie';}
                if(this.type == 8){ return 'product';}
                if(this.type == 9){ return 'colloque';}

                return null;
            },
            hasTitle: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            hasText: function () {
                return (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            hasImage: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 3) || (this.type == 4) ? true : false;
            },
            uniqueid: function () {
                if(this.type == 5){return this.bloc.arret_id;}
                if(this.type == 7 || this.type == 10){return this.bloc.categorie_id;}
                if(this.type == 8){ return this.bloc.product_id;}
                if(this.type == 9){ return this.bloc.colloque_id;}

                return null;
            },
            color(){
                return this.newsletter.color;
            }
        },
        mounted: function ()  {
            this.initialize();
            this.makeHash();
        },
        methods: {
            makeVisible(){
                this.visible = this.visible ? false : true;
                this.activ   = this.visible ? false : true;

                this.selected = this.uniqueid ? this.uniqueid : null;

                if(this.visible && this.type != 7){
                    this.hideOriginal();
                }
                else{
                    this.showOriginal();
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
                        linkNewTab: true,
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
            hideOriginal(){
                this.$nextTick(function(){
                    console.log($('#'+ this.bloc_id));
                    $('#'+ this.bloc_id).hide();
                    $('#btn'+ this.bloc_id).hide();
                });
            },
            showOriginal(){
                this.$nextTick(function(){
                    $('#'+ this.bloc_id).show();
                    $('#btn'+ this.bloc_id).show();
                });
            },
            makeHash(){
                this.hash = Math.random().toString(36).substring(7);
            },
            initialize : function(){
                this.getSingle();
                this.getModels();
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
                    this.arrets = this.bloc.model && this.bloc.model.listearrets ? this.bloc.model.listearrets : [];
                }
            },
            getSingle: function() {
                var self = this;
                if(this.path){
                     var id = this.selected ? this.selected : this.uniqueid;
                     axios.get('admin/ajax/single/'+ this.path +'/' + id).then(function (response) {
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
                this.makeVisible();
            }
        }
    }
</script>
