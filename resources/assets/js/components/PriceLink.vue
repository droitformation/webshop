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
                           <dt>Type de prix:</dt>
                           <dd>
                               <select class="form-control" v-model="nouveau.type">
                                   <option value="public">Public</option>
                                   <option value="admin">Admin</option>
                               </select>
                           </dd>
                       </dl>
                   </div>
               </div>

               <div class="row choix_colloques_wrapper">
                   <div class="col-md-12">
                       <label class="choix_colloques"><strong>Choix des colloques lié</strong></label>
                       <select class="chosen-select-colloque form-control" multiple="multiple"></select>
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

               <div class="row choix_colloques_wrapper">
                   <div class="col-md-12">
                       <label class="choix_colloques"><strong>Choix des colloques liés</strong></label>
                       <div v-show="price.state">
                           <select class="chosen-select-colloque-edit form-control" multiple="multiple"></select>
                       </div>
                       <div v-show="!price.state">
                           <p v-for="colloque in price.linked">{{ colloque.text }}</p>
                       </div>
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
    .choix_colloques{
        width: 100%;
        margin-bottom:20px;
    }
    .choix_colloques_wrapper{
        margin-bottom:20px;
    }
</style>
<script>

export default {

    props: ['colloque','prices','all'],
    data () {
        return {
            list:this.prices,
            current:[],
            nouveau:{
                description: '',
                price: '',
                type: 'public',
                rang: '',
                remarque: '',
                colloques: [],
            },
            add : false,
            url: location.protocol + "//" + location.host+"/",
        }
    },
    computed: {
        relations: function () {
            var colloques = this.nouveau.colloques;
            colloques.push(this.colloque);

            return colloques;
        },
        choosen: function () {
            var colloques = this.current;
            colloques.push(this.colloque);

            return this.current;
        },
    },
    mounted: function ()  {
        let self = this;
        this.$nextTick(function() {
            let select = $('.chosen-select-colloque').select2({width:'100%', data: self.all});
            select.on('select2:select', function (e) {
                self.nouveau.colloques = select.select2('data').map(function(elem){return elem.id});
            });
            select.on('select2:unselect', function (e) {
                self.nouveau.colloques = select.select2('data').map(function(elem){return elem.id});
            });
        });
    },
    methods: {
        ajouter:function(){
            this.add = true;
        },
        editPrice : function(price){
            price.state = true;
            this.select(price);
        },
        select(price){
            this.current = _.map(price.linked, 'id');


            let self = this;
            this.$nextTick(function() {
                let edit = $('.chosen-select-colloque-edit').select2({width:'100%', data: self.all});
                edit.on('select2:select', function (e) {
                    self.current = edit.select2('data').map(function(elem){return elem.id});
                });
                edit.on('select2:unselect', function (e) {
                    self.current = edit.select2('data').map(function(elem){return elem.id});
                });
                edit.val(this.choosen).trigger('change');
            });

            console.log(this.choosen);
        },
        resetform :function(){
            this.add = false;
            this.nouveau = {
                description: '',
                price: '',
                type: '',
                rang: '',
                remarque: '',
                colloques: [],
            };
        },
        ajouterPrice:function(){
            var self = this;
            axios.post('/vue/price_link', {price: this.nouveau, relations: this.relations, colloque_id : this.colloque }).then(function (response) {
                self.list = response.data.prices;
                self.resetform();
            }).catch(function (error) { console.log(error);});
        },
        savePrice : function(price){
            var self = this;
            axios.post('/vue/price_link/' + price.id, {price, colloque_id: this.colloque, relations: this.choosen, '_method' : 'put'}).then(function (response) {
                self.list = response.data.prices;
            }).catch(function (error) { console.log(error);});
        },
        deletePrice :function(price){
            var self = this;
            axios.post('/vue/price_link/' + price.id + '/' + this.colloque, {'_method' : 'DELETE'}).then(function (response) {
                self.list = response.data.prices;
            }).catch(function (error) { console.log(error);});
        }
    }
}
</script>