<template>
    <div>
        <button :id="'btn'+bloc_id" v-show="activ" class="btn btn-info btn-xs pull-right" type="button" @click="makeVisible">editer</button>

        <arret v-if="type == 5 && model && visible" class="paddingUp" :newsletter="newsletter" :arret="model"></arret>

        <div class="wrapper-bloc-edit" v-if="visible">
            <div class="edit_bloc_form">
                <form name="blocForm newsletterForm" method="post" :action="action">

                    <input name="_token" :value="_token" type="hidden">
                    <div class="panel panel-success">
                        <div class="panel-body">

                            <div v-if="type == 10 || type == 5 || type == 9 || type == 8 || type == 7">
                                <select class="form-control form-required required" @change="getSingle(selected)" v-model="selected" name="model_id">
                                    <option v-if="!selected" :value="null" disabled>Sélectionner</option>
                                    <option v-for="model in models" v-bind:value="model.id">{{ model.title }}</option>
                                </select><br/>
                            </div>

                            <div class="form-group">
                                <div class="btn-group">
                                    <input type="hidden" :value="bloc.id" name="id">
                                    <input type="hidden" :value="type" name="type_id">
                                    <input type="hidden" :value="campagne.id" name="campagne">

                                    <input v-if="model" type="hidden" :name="path + '_id'" :value="model.id" />

                                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
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
    left:615px;
}
.edit_bloc_form{
    width: 630px;
}
.edit_bloc_form::before{
        color: #85c744;
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
</style>
<script>

    import Arret from './blocs/Arret.vue';

    export default{

        props: ['bloc','campagne','newsletter','type','_token','url','site'],
        components:{
            'arret' : Arret,
        },
        data(){
            return{
                visible: false,
                activ:true,
                models: [],
                selected:null,
                hash: null,
                model:null,
            }
        },
        watch: {

        },
        computed: {
            bloc_id(){
                return 'bloc_' + this.bloc.id;
            },
            action:function(){
                return this.url;
            },
            path: function () {
                if(this.type == 5){return 'arret';}
                if(this.type == 7 || this.type == 10){return 'categorie';}
                if(this.type == 8){ return 'product';}
                if(this.type == 9){ return 'colloque';}

                return null;
            },
            uniqueid: function () {
                if(this.type == 5){return 'arret_id';}
                if(this.type == 7 || this.type == 10){return 'categorie_id';}
                if(this.type == 8){ return 'product_id';}
                if(this.type == 9){ return 'colloque_id';}

                return null;
            },
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            makeVisible(){
                this.visible = this.visible ? false : true;
                this.activ = this.visible ? false : true;

                if(this.visible){
                    this.hideOriginal();
                }
            },
            hideOriginal(){
                this.$nextTick(function(){
                    console.log($('#'+ this.bloc_id));
                    $('#'+ this.bloc_id).hide();
                    $('#btn'+ this.bloc_id).hide();
                });
            },
            makeHash(){
                this.hash = Math.random().toString(36).substring(7);
            },
            initialize : function(){

                this.getSingle();
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
                                self.contenu = this.source.getCode();
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
                    var id = this.selected ? this.selected : this.bloc.arret_id;
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
