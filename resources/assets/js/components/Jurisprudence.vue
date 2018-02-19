
<template>
    <div>

        <div class="row">
            <div class="col-md-8">
                <h3 class="line">Jurisprudence</h3>

                <p id="loader-app" v-show="loading"><i class="fa fa-spinner fa-spin"></i></p>
                <p v-if="blocs.length === 0" v-show="!loading" class="text-danger"><i class="fa fa-exclamation-triangle"></i> &nbsp;Aucun arrêt pour cette recherche</p>

                <section v-show="!loading">

                    <article class="row" v-for="arret in blocs">
                        <div class="col-md-9">
                            <div class="post">
                                <div class="post-title">
                                    <h3>{{ arret.title }}</h3>
                                    <p class="text-abstract-app">{{ arret.abstract }}</p>
                                 </div>
                                <div class="post-entry">
                                    <div v-html="arret.pub_text"></div>
                                    <a target="_blank" :href="arret.document" v-if="arret.document">Télécharger en pdf &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i></a>

                                    <!-- Ananlyse -->
                                    <div class="analyse-app" v-for="analyse in arret.analyses">
                                        <div class="well well-app">
                                            <h3>Analyse de {{ analyse.auteurs }}</h3>
                                            <p class="text-muted">{{ analyse.date }}</p>
                                            <p class="text-abstract-app">{{ analyse.abstract }}</p>
                                            <a target="_blank" :href="analyse.document" v-if="analyse.document">Télécharger en pdf &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i></a>
                                        </div>
                                    </div>
                                    <!-- END Ananlyse -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="list-cat-app" v-for="cat in arret.categories">
                                <img width="130" :alt="cat.title" :src="url +'files/pictos/' + cat.image">
                                <p><small>{{ cat.title }}</small></p>
                            </div>
                        </div>

                    </article>

                </section>
            </div>
            <div class="col-md-4">
                <div class="sidebar-app fixed">

                    <a :href="url + slug" class="btn btn-default btn-block"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour accueil</a>

                    <div class="widget clear">
                        <h3 class="title"><i class="icon-tasks"></i> &nbsp;Affichage</h3>
                        <p><input type="checkbox" name="display" v-on:change="filterYears" v-model="display">
                            <label>Que les arrêts avec analyses</label></p>
                    </div>

                    <div class="widget categories clear">
                        <h3 class="title"><i class="icon-tasks"></i> &nbsp;Catégories</h3>
                        <select id="chosen-select-app" data-placeholder="Choisir une ou plusieurs catégories" style="width:100%" multiple class="chosen-select category">
                            <option v-for="categorie in list" v-bind:value="categorie.id">{{ categorie.title}}</option>
                        </select>
                    </div>

                    <div class="widget years clear">
                        <h3 class="title"><i class="icon-calendar"></i> &nbsp;Années</h3>
                        <p v-for="annee in annees">
                           <input type="checkbox" v-on:change="filterYears" v-model="checked" :value="annee">
                           <label :for="annee">Paru en {{ annee }}</label>
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>
<style>

    #chosen-select-app{
        z-index:100;
        width: 100% !important; /* or any value that fits your needs */
    }

    #loader-app{
        text-align:center;
        width:100%;
        font-size:30px;
        height:30px;
        line-height:30px;
    }

</style>
<script>

    export default {

        props: ['site','years','categories','slug'],
        data () {
            return {
                list : [],
                annees : [],
                blocs: [],
                checked: [],
                loading: false,
                display:0,
                url: location.protocol + "//" + location.host+"/",
            }
        },
        computed: {
            computedSite: function () {
                return this.site
            }
        },
        mounted: function ()  {

            this.getCategories();
            this.getAnnees();

            let self = this;
            this.loading = true;

            this.changed([],[]);

            this.$nextTick(function() {

                 let self = this;

                 $('#chosen-select-app').chosen();
                 $("#chosen-select-app").chosen().change(function(evt,params){

                     var categories = $(this).val();
                     var years      = self.checked ? self.checked : null;

                     self.changed(categories , years);
                 });
            });
        },
        methods: {
            filterYears : function(){
                 var categories = $('#chosen-select-app').val();
                 var years      = this.checked ? this.checked : null;

                 this.changed(categories, years);
            },
            getCategories : function(){
               this.list = this.categories;
            },
            getAnnees : function(){
               this.annees = this.years;
            },
            updateArrets : function(arrets){
               this.blocs = arrets;
            },
            changed: function(selected, checked) {
                this.loading = true;

                var self = this;
                axios.post('/vue/arrets', { site: this.site, categories : selected, years : checked, display : this.display  }).then(function (response) {
                      setTimeout(function(){
                           self.updateArrets(response.data.arrets);
                           self.loading = false;
                      }, 500);
                }).catch(function (error) { console.log(error);});
            },
        }
    }
</script>