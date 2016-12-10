
<template>
    <div>

        <div class="row">
            <div class="col-md-8">

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
                                    <a :href="arret.document" v-if="arret.document">Télécharger en pdf &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i></a>

                                    <!-- Ananlyse -->
                                    <div class="analyse-app" v-for="analyse in arret.analyses">
                                        <b-collapse-toggle v-bind:target="'reference_' + analyse.id" v-bind:target-group="'analyse_' + arret.id">
                                            <div><a class="btn btn-sm btn-matrimonial" href="#"><i class="fa fa-file"></i> &nbsp;Voir l'analyse</a></div>
                                        </b-collapse-toggle>
                                        <b-collapse v-bind:id="'reference_' + analyse.id" v-bind:group="'analyse_' + arret.id">
                                            <div class="well well-app">
                                                <h3>Analyse de {{ analyse.auteurs }}</h3>
                                                <p class="text-muted">{{ analyse.date }}</p>
                                                <p class="text-abstract-app">{{ analyse.abstract }}</p>
                                                <a :href="analyse.document" v-if="analyse.document">Télécharger en pdf &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i></a>
                                            </div>
                                        </b-collapse>
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

                    <!-- pagination -->
                    <nav>
                        <ul class="pagination">
                            <li v-if="pagination.current_page > 1">
                                <a href="#" @click.prevent="changePage(pagination.current_page - 1)"><span aria-hidden="true">&laquo;</span></a>
                            </li>
                            <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
                                <a href="#" @click.prevent="changePage(page)">{{ page }}</a>
                            </li>
                            <li v-if="pagination.current_page < pagination.last_page">
                                <a href="#" @click.prevent="changePage(pagination.current_page + 1)"><span aria-hidden="true">&raquo;</span></a>
                            </li>
                        </ul>
                    </nav>
                    <!-- pagination -->

                </section>
            </div>
            <div class="col-md-4">
                <div class="sidebar-app">

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
                pagination: {
                    total: 0,
                    per_page: 10,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,// left and right padding from the pagination <span>,just change it to see effects
            }
        },
        computed: {
            computedSite: function () {
                return this.site
            },
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        mounted: function ()  {

            this.getCategories();
            this.getAnnees();

            let self = this;
            this.loading = true;

            this.changed(this.pagination.current_page,[],[]);

            this.$nextTick(function() {

                 let self = this;

                 $('#chosen-select-app').chosen();
                 $("#chosen-select-app").chosen().change(function(evt,params){

                     var categories = $(this).val();
                     var years      = self.checked ? self.checked : null;

                     self.changed(1, categories , years);
                 });
            });
        },
        methods: {
            filterYears : function(){
                 var categories = $('#chosen-select-app').val();
                 var years      = this.checked ? this.checked : null;

                 this.changed(1,categories, years);
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
            updatePagination : function(pagination){
               this.pagination = pagination;
            },
            changed: function(page, selected, checked) {
                  this.loading = true;

                  // POST
                  this.$http.post('/vue/arrets', { site: this.site, categories : selected, years : checked, page: page, display : this.display  }).then((response) => {

                      var self = this;
                      //small delay for pdf generation completion
                      setTimeout(function(){

                           self.updateArrets(response.body.arrets);
                           self.updatePagination(response.body.pagination);

                           self.loading = false;
                      }, 500);

                  }, (response) => {
                    // error callback
                  }).bind(this);
            },
            changePage: function (page) {
                this.pagination.current_page = page;

                var categories = $('#chosen-select-app').val();
                var years      = this.checked ? this.checked : null;

                this.changed(page, categories, years);
            }
        }
    }
</script>