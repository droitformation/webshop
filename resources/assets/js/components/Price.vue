
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
                           <dd><input class="form-control" name="description" type="text" v-model="nouveau.description" v-bind:value="nouveau.description"></dd>
                           <dt>Remarque:</dt>
                           <dd><input class="form-control" name="remarque" type="text" v-model="nouveau.remarque" v-bind:value="nouveau.remarque"></dd>
                       </dl>
                   </div>
                   <div class="col-md-6">
                       <dl class="dl-horizontal price-list">
                           <dt>Prix:</dt>
                           <dd><input class="form-control" name="remarque" type="text" v-model="nouveau.price" v-bind:value="nouveau.price"></dd>
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
                               <input class="form-control" name="description" type="text" v-model="price.description" v-bind:value="price.description">
                           </dd>
                           <dt>Remarque:</dt>
                           <dd v-if="!price.state">{{ price.remarque }}</dd>
                           <dd v-if="price.state">
                               <input class="form-control" name="remarque" type="text" v-model="price.remarque" v-bind:value="price.remarque">
                           </dd>
                       </dl>
                   </div>
                   <div class="col-md-6">
                       <dl class="dl-horizontal price-list">
                           <dt>Prix:</dt>
                           <dd v-if="!price.state">{{ price.price }} CHF</dd>
                           <dd v-if="price.state">
                               <input class="form-control" name="remarque" type="text" v-model="price.price" v-bind:value="price.price">
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

    props: ['colloque','prices'],
    data () {
        return {
            list: [],
            nouveau:{
                description: '',
                price: '',
                type: 'public',
                rang: '',
                remarque: '',
                colloque_id: this.colloque,
            },
            add : false
        }
    },
    beforeMount: function () {
        this.getPrices();
    },
    methods: {
        getPrices : function(){
             this.list = _.orderBy(this.prices, ['type'],['desc']);
        },
        editPrice : function(price){
            price.state = true;
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

            this.$http.post('/vue/price', { price : this.nouveau }).then((response) => {
                this.list = _.orderBy(response.body.prices, ['type'],['desc']);
                this.resetform();
            }, (response) => {}).bind(this);
        },
        savePrice : function(price){

            //var model = _.find(this.prices, function(model) { return model.id == price.id });

            this.$http.post('/vue/price/' + price.id, { price, '_method' : 'put' }).then((response) => {
               this.list = _.orderBy(response.body.prices, ['type'],['desc']);
            }, (response) => {}).bind(this);

        },
        deletePrice :function(price){

            //var model = _.find(this.prices, function(model) { return model.id == price.id });

            this.$http.post('/vue/price/' + price.id, { '_method' : 'DELETE' }).then((response) => {
                this.list = _.orderBy(response.body.prices, ['type'],['desc']);
            }, (response) => {}).bind(this);
        }
    }
}
</script>