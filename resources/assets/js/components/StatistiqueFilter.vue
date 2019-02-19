<template>
   <div>
      <div class="row">
         <div class="col-lg-2 col-md-2 col-xs-12">
            <div class="input-group">
               <span class="input-group-addon">Quoi</span>
               <select class="form-control" v-model="model" @change="getModels" name="model">
                  <option value="inscription">Inscriptions</option>
                  <option value="order">Commandes</option>
                  <option value="abo">Abonnements</option>
               </select>
            </div>
         </div>
         <div class="col-lg-2 col-md-3 col-xs-12">
            <div class="input-group">
               <span class="input-group-addon">Du</span>
               <input type="text" name="period[start]" class="form-control datePicker" value="" placeholder="Début">
            </div>
         </div>
         <div class="col-lg-2 col-md-3 col-xs-12">
            <div class="input-group">
               <span class="input-group-addon">au</span>
               <input type="text" name="period[end]" class="form-control datePicker" value="" placeholder="Fin">
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-xs-12">
            <div class="input-group">
               <span class="input-group-addon">Agrégats</span>
               <select class="form-control" name="sum">
                  <option value="">Choix</option>
                  <option v-if="model == 'order'" value="sum-price">Somme prix par commandes</option>
                  <option v-if="model == 'order'" value="sum-product">Somme vente par livres</option>
                  <option v-if="model == 'order'" value="sum-title">Nombre de vente par livres</option>
                  <option v-if="model == 'inscription'" value="sum-price">Somme prix inscriptions</option>
               </select>
            </div>
         </div>
      </div>
      <div style="margin-top: 20px; padding:10px;background:#f1f1f1;" >
         <div class="row">
            <div class="col-lg-4 col-md-4 col-xs-12" v-for="(filters,index) in models">
               <div class="input-group">
                  <span class="input-group-addon">{{ index }}</span>
                  <select class="form-control form-required required select-filter" :rel="index" multiple :name="'sort['+index+'][]'">
                     <option v-for="model in filters" required v-bind:value="model.id">{{ model.title }}</option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <button class="btn btn-primary" style="margin-top: 20px;" type="submit">Rechercher</button>
   </div>
</template>
<style>
   select.form-control + .chosen-container-multi .chosen-choices{
      border-bottom-left-radius: 0;
      border-top-left-radius: 0;
   }

   select.form-control + .chosen-container-multi .chosen-choices {
      min-height: 30px;
      padding: 1px 4px 2px 4px;
      border-radius: 0;
      line-height: 30px;
   }
</style>
<script>
export default {

    props: ['start','end'],
    data () {
        return {
            model:'order',
            name:null,
            sum:null,
            filters:[],
            models:[],
            choosen:[]
        }
    },
    computed: {
    },
    mounted: function ()  {
       this.getModels();
       this.$nextTick(function() {

          let self = this;

          $('.select-filter').select2({ placeholder: "Choix"});
          $('.select-filter').on('select2:select', function (e) {
             let value = $(this).val();
             let filter = $(this).attr('rel');
             console.log(filter);
          });
          $('.select-filter').on('select2:unselect', function (e) {
             let value = $(this).val();
             let filter = $(this).attr('rel');
             console.log(filter);
          });

       });
    },
    methods: {
       updateFilters:function(event){
          console.log(event.target.value);
       },
       getModels: function() {
          let self = this;

          self.models = [];

          setTimeout(function() {
             $('.select-filter').trigger('change.select2');
          },100);

          axios.post('vue/models',{model: self.model}).then(function (response) {

             self.models = response.data;
             setTimeout(function() {
                $('.select-filter').trigger('change.select2');
             },100);

          }).catch(function (error) { console.log(error);});

       },
    }
}
</script>