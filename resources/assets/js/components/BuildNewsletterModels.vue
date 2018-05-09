<template>
    <div>
        <div class="row">
            <div class="col-md-7" id="StyleNewsletterCreate">

                <div class="btn-group pull-right" v-if="mode == 'edit'">
                    <button v-if="model && !isEdit" @click="editMode(model)" class="btn btn-xs btn-warning">éditer</button>
                    <button v-if="model && !isEdit" @click="deleteContent(content)" class="btn btn-xs btn-danger">x</button>
                </div>

                <analyse-newsletter v-if="model && type == 5 && site != 4" :arret="model" :analyses="model.analyses"></analyse-newsletter>

                <!-- Bloc content-->
                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
                    <tr v-if="model">
                        <td valign="top" width="375" class="resetMarge contentForm">
                            <h3>{{ model.title }}</h3>
                            <p class="abstract">{{ model.abstract }}</p>
                            <div v-html="model.content" class="content"></div>
                            <p><a :class="model.class" :href="model.link">{{ model.message }}</a></p>
                        </td>

                        <!-- Bloc image droite-->
                        <td width="25" class="resetMarge"></td><!-- space -->
                        <td valign="top" align="center" width="160" class="resetMarge">
                            <p v-for="image in model.images">
                                <a target="_blank" :href="image.link">
                                    <img width="130" border="0" :alt="image.title" :src="image.image">
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- Bloc content-->

                <analyse-newsletter v-if="model && type == 5 && site == 4" :arret="model" :analyses="model.analyses"></analyse-newsletter>

            </div>

            <div class="col-md-5" v-show="isEdit || mode == 'create'">
                <form name="blocForm" class="form-horizontal" method="post" :action="action">

                    <input name="_token" :value="_token" type="hidden">
                    <input v-if="mode == 'edit'" type="hidden" name="_method" value="PUT">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <h3>{{ title }}</h3>

                            <div v-if="type == 7">
                                <select class="form-control form-required required" v-model="categorie" name="id" v-on:change="updateModel">
                                    <option v-if="!categorie" :value="null" disabled>Sélectionner catégorie</option>
                                    <option v-for="categorie in categories" v-bind:value="categorie">{{ categorie.title }}</option>
                                </select><br/>

                                <div class="row drag">
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
                            </div>
                            <div v-if="type != 7">
                                <select class="form-control form-required required" v-model="model" name="id" v-on:change="updateModel">
                                    <option v-if="!model" :value="null" disabled>Sélectionner</option>
                                    <option v-for="model in models" v-bind:value="model">{{ model.title }}</option>
                                </select><br/>
                            </div>

                            <div class="btn-group">
                                <input type="hidden" :value="type" name="type_id">
                                <input type="hidden" :value="campagne.id" name="campagne">
                                <input v-if="type == 7 && categorie" type="hidden" :value="categorie.id" name="categorie_id">

                                <div v-if="model">
                                    <input v-if="type == 5" type="hidden" :value="model.id" name="arret_id">
                                    <input v-if="type == 8" type="hidden" :value="model.id" name="product_id">
                                    <input v-if="type == 9" type="hidden" :value="model.id" name="colloque_id">
                                </div>

                                <div v-if="type == 7">
                                    <input v-for="chose in choosen" type="hidden" name="arrets[]" :value="chose.id" />
                                </div>

                                <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                                <button type="button" @click="close" class="btn btn-sm btn-default cancelCreate">Annuler</button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>
<style>

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

    import draggable from 'vuedraggable';
    import AnalyseNewsletter from './partials/AnalyseNewsletter.vue';

    export default{

        props: ['type','campagne','_token','url','site','title','content','mode'],
        components: {
            draggable,
            'analyse-newsletter' : AnalyseNewsletter,
        },
        data(){
            return{
                model:null,
                choosen: [],
                categorie: null,
                categories: [],
                arrets: [],
                models: [],
                lists:[],
                isEdit: false,
            }
        },
        computed: {
            route: function () {
                if(this.type == 5){ return "admin/ajax/arrets/" + this.site; }
                if(this.type == 8){ return "admin/ajax/product"; }
                if(this.type == 9){ return "admin/ajax/colloque"; }
            },
            selected: function () {
                if(this.type == 5){ this.content.arret }
                if(this.type == 8){ this.content.product }
                if(this.type == 9){ this.content.colloque }
            },
            action:function(){
                if(this.mode == 'edit'){ return this.url + '/' + this.content.id; }
                if(this.mode == 'create'){ return this.url; }
            },
            prepared: function () {
                var arr = [];
                _.each(this.choosen,function(o){
                   arr.push(_.pick(o,'id'));
                });

                return arr;
            }
        },
        watch : {
           type:function(val) {
              this.models = [];
              this.getModels(this.route);
           },
        },
        mounted: function ()  {
            this.getModels(this.route);
            this.getCategories();
            this.initialize();
        },
        methods: {
            initialize : function(){
                this.model  = this.content ? this.content.model : null;
                this.isEdit = !this.content ? true : false;
            },
            getModels: function(route) {
                var self = this;
                axios.get(route).then(function (response) {
                      self.models = response.data;
                }).catch(function (error) { console.log(error);});
            },
            getCategories: function() {
                var self = this;
                axios.get('admin/ajax/categories/' + self.site).then(function (response) {
                      self.categories = response.data;
                      self.lists.push(self.categories);
                }).catch(function (error) { console.log(error);});
            },
            getArretsCategories: function() {
                var self = this;
                axios.post("admin/ajax/categorie/categoriearrets",{ id: self.categorie.id }).then(function (response) {
                      self.arrets = response.data;
                }).catch(function (error) { console.log(error);});
            },
            updateModel(){
                this.getArretsCategories();
            },
            editMode(model){
                this.isEdit = true;
            },
            close(){
                this.isEdit = false;
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
