
<template>
    <div>
        <div class="row">

            <div class="col-md-8">
                <i v-show="loading" class="fa fa-spinner fa-spin"></i>

                <article class="row" v-for="arret in blocs">
                    <div class="col-md-8">
                        <div class="post">
                            <div class="post-title">
                                <h3>{{ arret.title }}</h3>
                                <p>{{ arret.abstract }}</p>
                             </div>
                            <div class="post-entry">
                                <a class="anchor" :name="arret.reference"></a>
                                <div v-html="arret.pub_text"></div>
                                <a :href="arret.document" v-if="arret.document">Télécharger en pdf &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div v-for="cat in arret.categories">
                            <img style="max-width: 140px;" border="0" :alt="cat.title" :src="url +'files/pictos/' + cat.image">
                            <p><small>{{ cat.title }}</small></p>
                        </div>
                    </div>
                </article>

            </div>
            <div class="col-md-4">
                <div class="sidebar">
                    <div class="widget list categories clear">
                        <h3 class="title"><i class="icon-tasks"></i> &nbsp;Catégories</h3>
                        <select id="chosen-select-app" data-placeholder="Choisir une ou plusieurs catégories" style="width:100%" multiple class="chosen-select category">
                            <option v-for="categorie in list" v-bind:value="categorie.id">{{ categorie.title}}</option>
                        </select>
                    </div><!--END WIDGET-->
                </div>
            </div>

        </div>
    </div>
</template>
<style>
    #chosen-select-app{
        z-index:100;
    }
</style>
<script>

    export default {

        props: ['site','categories','path'],
        data () {
            return {
                list : [],
                blocs: [],
                loading: false,
                url: location.protocol + "//" + location.host+"/"
            }
        },
        computed: {
           computedSite: function () {
                return this.site
            },
        },
        mounted: function ()  {
            this.getCategories();
            this.loading = false;

            let self = this;

            this.$nextTick(function() {

                 let self = this;
                 $('#chosen-select-app').chosen();

                 $("#chosen-select-app").chosen().change(function(evt,params){
                     console.log(params);
                     console.log($(this).val());
                     self.changed($(this).val());
                 });
            });

        },
        methods: {
            getCategories : function(){
               this.list = this.categories;
            },
            updateArrets : function(arrets){
               this.blocs = arrets;

            },
            changed: function(selected) {
                  this.loading = true;

                  // POST
                  this.$http.post('/vue/arrets', { site: this.site, selected : selected }).then((response) => {

                      var self = this;
                      //small delay for pdf generation completion
                      setTimeout(function(){
                           self.updateArrets(response.body.arrets);
                           self.loading = false;
                           console.log(response.body.arrets);
                      }, 500);

                  }, (response) => {
                    // error callback
                  }).bind(this);
            }
        }
    }
</script>