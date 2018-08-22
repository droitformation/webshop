<template>
    <div>
        <div class="row">

            <div class="col-md-7" id="StyleNewsletterCreate">

                <div class="btn-group pull-right">
                    <form method="post" :action="action" v-if="!isEdit">
                        <input name="_token" :value="_token" type="hidden">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" :value="content.id" />
                        <input type="hidden" :value="campagne.id" name="campagne_id">
                        <button v-if="!isEdit" @click="editMode(content)" type="button" class="btn btn-xs btn-warning">éditer</button>
                        <button type="submit" class="btn btn-xs btn-danger deleteNewsAction" :data-id="content.id" data-action="Groupe">x</button>
                    </form>
                </div>

                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="tableReset" v-if="categorie">
                    <tr bgcolor="ffffff"><td height="15"></td></tr><!-- space -->
                    <tr>
                        <td width="400" align="left" class="resetMarge contentForm" valign="top">
                            <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ categorie.title }}</h3>
                        </td>
                        <td valign="top" align="center" width="160" class="resetMarge">
                            <div><img width="130" border="0" :alt="categorie.title" :src="content.model.image + '/' + categorie.image"></div>
                        </td>
                    </tr><!-- space -->
                    <tr bgcolor="ffffff"><td height="15"></td></tr><!-- space -->
                </table>

                <div v-for="arret in lists">
                    <!-- Bloc content-->
                    <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
                        <tr bgcolor="ffffff"><td height="5"></td></tr><!-- space -->
                        <tr v-if="arret">
                            <td valign="top" width="375" class="resetMarge contentForm">
                                <h3>{{ arret.dumois ? 'Arrêt du mois : ' : '' }}{{ arret.title }}</h3>
                                <p class="abstract">{{ arret.abstract }}</p>
                                <div v-html="arret.content" class="content"></div>
                                <p><a target="_blank" :class="arret.class" :href="arret.link">{{ arret.message }}</a></p>
                            </td>

                            <!-- Bloc image droite-->
                            <td width="25" class="resetMarge"></td><!-- space -->
                            <td valign="top" align="center" width="160" class="resetMarge">
                                <div v-for="image in arret.images" v-if="image.id != categorie.id">
                                    <a target="_blank" :href="image.link">
                                        <img width="130" border="0" :alt="image.title" :src="image.image">
                                    </a>
                                    <p v-if="!newsletter.hide_title" style="text-align:center !important;">{{ image.title }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="ffffff"><td height="5"></td></tr><!-- space -->
                    </table>
                    <!-- Bloc content-->

                </div>
            </div>

            <div class="col-md-5" v-show="isEdit">
                <form name="blocForm newsletterForm" class="form-horizontal" method="post" :action="url + '/' + content.id">
                    <input name="_token" :value="_token" type="hidden">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <h3>{{ title }}</h3>

                            <select class="form-control form-required required" v-model="categorie" name="id" v-on:change="updateModel">
                                <option :value="null" disabled>Sélectionner</option>
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

                            <div class="btn-group">
                                <input type="hidden" :value="type" name="type_id">
                                <input type="hidden" :value="campagne.id" name="campagne">
                                <input v-if="categorie" type="hidden" :value="categorie.id" name="categorie_id">
                                <input type="hidden" :value="content.groupe_id" name="groupe_id">
                                <input v-for="chose in choosen" type="hidden" name="arrets[]" :value="chose.id" />
                                <input type="hidden" name="id" :value="content.id" />
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
    export default{

        props: ['type','campagne','_token','url','site','title','content','mode','newsletter'],
        components: {
            draggable,
        },
        data(){
            return{
                choosen: [],
                categorie: null,
                categories: [],
                arrets: [],
                lists:[],
                isEdit: false,
            }
        },
        computed: {
            prepared: function () {
                var arr = [];
                _.each(this.choosen,function(o){
                   arr.push(_.pick(o,'id'));
                });

                return arr;
            },
            action:function(){
                if(this.mode == 'edit'){ return this.url + '/' + this.content.id; }
                if(this.mode == 'create'){ return this.url; }
            }
        },
        mounted: function ()  {
            this.getCategories();
            this.initialize();
        },
        methods: {
            initialize : function(){
                this.choosen = this.content.model.choosen;
                this.lists   = this.content.model.arrets;
                this.categorie = this.content.model.categorie;

                this.getArretsCategories();
            },
            getCategories: function() {
                var self = this;
                axios.get('admin/ajax/categories/' + self.site).then(function (response) {
                      self.categories = response.data;
                      self.lists.push(self.categories);
                      self.categorie = self.content ? self.content.model.categorie : null;
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
            },
            deleteContent(model){
                this.$emit('deleteContent', model);
            }
        }
    }
</script>
