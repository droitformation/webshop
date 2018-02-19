
<template>
   <div>
       <div class="text-right">
           <div class="btn-pull" style="margin-bottom:10px;">
               <a v-show="!add" @click="ajouter" class="btn btn-sm btn-success">Ajouter</a>
               <a v-show="add" @click="resetform" class="btn btn-sm btn-default">Fermer</a>
           </div>
       </div>
       <ul class="list-group">
           <li class="list-group-item" id="addPrices" v-show="add">
               <div class="row">
                   <div class="col-md-6">
                       <dl class="dl-horizontal price-list">
                           <dt>Description:</dt>
                           <dd><input class="form-control" name="description" type="text" v-model="nouveau.description"></dd>
                           <dt>Remarque:</dt>
                           <dd><input class="form-control" name="remarque" type="text" v-model="nouveau.remarque"></dd>
                       </dl>
                   </div>
                   <div class="col-md-6">
                       <dl class="dl-horizontal price-list">
                           <dt>Prix:</dt>
                           <dd><input class="form-control" name="remarque" type="text" v-model="nouveau.price"></dd>
                           <dt>Type de prix::</dt>
                           <dd>
                               <select class="form-control" v-model="nouveau.type">
                                   <option value="public">Public</option>
                                   <option value="admin">Admin</option>
                               </select>
                           </dd>
                       </dl>
                   </div>
               </div>
               <div class="row">
                   <div class="col-md-12">
                       <dl class="dl-horizontal price-list">
                           <dt style="width:200px;">Cacher le prix à partir du:</dt>
                           <dd style="margin-left:220px;"><input class="form-control datePickerNew" name="end_at" type="text" v-model="nouveau.end_at"></dd>
                       </dl>
                   </div>
               </div>

               <p class="text-right margBottom"><a @click="ajouterPrice" class="btn btn-sm btn-primary">Envoyer</a></p>
           </li>
       </ul>

       <ul class="list-group">
           <li v-for="price in list" :class="'list-group-item ' + price.type">

               <div class="row">
                   <div class="col-md-12 text-right">
                       <div class="btn-group" style="margin-bottom:10px;">
                           <a v-show="!price.state" @click="editPrice(price)" class="btn btn-xs btn-info">éditer</a>
                           <a v-show="!price.state" @click="deletePrice(price)" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
                           <a v-show="price.state" @click="savePrice(price)" class="btn btn-xs btn-primary">sauvegarder</a>
                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-md-6">
                       <dl class="dl-horizontal price-list">
                           <dt>Description:</dt>
                           <dd v-if="!price.state">{{ price.description }}</dd>
                           <dd v-if="price.state">
                               <input class="form-control" name="description" type="text" v-model="price.description">
                           </dd>
                           <dt>Remarque:</dt>
                           <dd v-if="!price.state">{{ price.remarque }}</dd>
                           <dd v-if="price.state">
                               <input class="form-control" name="remarque" type="text" v-model="price.remarque">
                           </dd>
                       </dl>
                   </div>
                   <div class="col-md-6">
                       <dl class="dl-horizontal price-list">
                           <dt>Prix:</dt>
                           <dd v-if="!price.state">{{ price.price }} CHF</dd>
                           <dd v-if="price.state">
                               <input class="form-control" name="remarque" type="text" v-model="price.price">
                           </dd>
                           <dt>Type de prix:</dt>
                           <dd v-if="!price.state">{{ price.type }}</dd>
                           <dd v-if="price.state">
                               <select class="form-control" v-model="price.type">
                                   <option value="public">Public</option>
                                   <option value="admin">Admin</option>
                               </select>
                           </dd>
                       </dl>
                   </div>
               </div>

               <div class="row">
                   <div class="col-md-12">
                       <dl class="dl-horizontal price-list">
                           <dt style="width:200px;">Cacher le prix à partir du:</dt>
                           <dd style="margin-left:220px;" v-if="!price.state">{{ price.end_at }}</dd>
                           <dd style="margin-left:220px;" v-if="price.state">
                               <input class="form-control datePickerPrices" name="end_at" type="text" v-model="price.end_at">
                           </dd>
                       </dl>
                   </div>
               </div>

           </li>
       </ul>

   </div>
</template>
<style>
    #addPrices{
        margin-bottom:15px;
    }
    .margBottom{
      padding-bottom:5px;
    }
    .list-group-item {
        padding: 8px 15px 8px 15px;
    }
    .price-list{
        width:100%;
    }
    .price-list dd {
        margin-left: 100px;
    }

</style>
<script>

export default {

    props: ['colloque','prices','occurrences'],
    data () {
        return {
            list: [],
            list_occurrences: [],
            nouveau:{
                description: '',
                price: '',
                type: 'public',
                rang: '',
                remarque: '',
                end_at:'',
                colloque_id: this.colloque,
            },
            add : false
        }
    },
    beforeMount: function () {
        this.getData();
    },
    methods: {
        getData : function(){
             this.list = _.orderBy(this.prices, ['type'],['desc']);
             this.list_occurrences = this.occurrences;

             this.$nextTick(function(){

               $.fn.datepicker.dates['fr'] = {
                    days: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
                    daysShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
                    daysMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
                    months: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
                    monthsShort: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
                    today: "Aujourd'hui",
                    clear: "Clear"
                };

                var self = this;

                $('.datePickerNew').datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'fr'
                }).on('changeDate', function(ev){
                   self.nouveau.end_at = ev.target.value;
                });
            });
        },
        editPrice : function(price){
            price.state = true;

            this.$nextTick(function(){
                 $.fn.datepicker.dates['fr'] = {
                    days: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
                    daysShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
                    daysMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
                    months: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
                    monthsShort: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
                    today: "Aujourd'hui",
                    clear: "Clear"
                };

                $('.datePickerPrices').datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'fr'
                }).on('changeDate', function(ev){
                   price.end_at = ev.target.value;
                });
            });

        },
        ajouter:function(){
            this.add = true;
        },
        resetform :function(){
            this.add = false;
            this.nouveau = {
                description: '',
                price: '',
                type: '',
                rang: '',
                remarque: '',
                colloque_id: this.colloque,
            };
        },
        ajouterPrice:function(){

            var self = this;
            axios.post('/vue/price', { price : this.nouveau }).then(function (response) {
                self.list = _.orderBy(response.data.prices, ['type'],['desc']);
                self.resetform();
            }).catch(function (error) { console.log(error);});
        },
        savePrice : function(price){

            var self = this;
            axios.post('/vue/price/' + price.id, { price, '_method' : 'put' }).then(function (response) {
               self.list = _.orderBy(response.data.prices, ['type'],['desc']);
            }).catch(function (error) { console.log(error);});

        },
        deletePrice :function(price){

            var self = this;
            axios.post('/vue/price/' + price.id, { '_method' : 'DELETE' }).then(function (response) {
                self.list = _.orderBy(response.data.prices, ['type'],['desc']);
            }).catch(function (error) { console.log(error);});
        }
    }
}
</script>