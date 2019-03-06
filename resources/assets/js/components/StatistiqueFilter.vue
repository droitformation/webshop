<template>
   <div>
      <div class="row">
         <div class="col-lg-2 col-md-2 col-xs-12">
            <div class="input-group">
               <span class="input-group-addon">Quoi</span>
               <select class="form-control" v-model="model" @change="getModels" name="model">
                  <option value="inscription">Inscriptions</option>
                  <option value="order">Commandes</option>
                  <option value="abonnement">Abonnements</option>
               </select>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-xs-12">
            <div class="input-group">
               <span class="input-group-addon">Agrégats</span>
               <select class="form-control" v-model="sum" name="sum">
                  <option value="">Choix</option>
                  <option v-if="model == 'order'" value="sum-price">Somme prix par commandes</option>
                  <option v-if="model == 'order'" value="sum-product">Somme vente par livres</option>
                  <option v-if="model == 'order'" value="sum-title">Nombre de vente par livres</option>
                  <option v-if="model == 'inscription'" value="sum-price">Somme prix inscriptions</option>
                  <option v-if="model == 'abonnement'" value="sum-status">Status</option>
                  <option v-if="model == 'abonnement'" value="sum-change">Nombre crée/supprimé par année</option>
               </select>
            </div>
         </div>
      </div>

      <div v-if="sum != 'sum-change'">
         <h4 style="margin-top: 20px;">Période</h4>
         <div class="row">
            <div class="col-lg-2 col-md-2 col-xs-12">
               <div class="input-group">
                  <span class="input-group-addon">Du</span>
                  <input type="text" name="period[start]" v-model="start" data-period="start" class="form-control datePickerStats" value="" placeholder="Début">
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12">
               <div class="input-group">
                  <span class="input-group-addon">au</span>
                  <input type="text" name="period[end]" v-model="end" data-period="end" class="form-control datePickerStats" value="" placeholder="Fin">
               </div>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12">
               <div class="input-group">
                  <span class="input-group-addon">Grouper par</span>
                  <select class="form-control" v-model="group" name="group">
                     <option value="">Choix</option>
                     <option value="month">Mois</option>
                     <option value="week">Semaine</option>
                     <option value="year">Année</option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <h4 style="margin-top: 20px;">Filtres</h4>
      <div style="padding:10px;background:#f1f1f1;" >
         <div class="row">
            <div class="col-lg-4 col-md-4 col-xs-12" v-for="(filters,index) in models">
                <label><strong>{{ index }}</strong></label>
                <select v-if="index != 'abo'" class="form-control select-filter-index" :v-model="'data'+index" :id="'select-filter-'+ index" :rel="index" multiple :name="'filters['+index+'][]'">
                    <option v-for="model in filters" required v-bind:value="model.id">{{ model.title }}</option>
                </select>

               <select v-if="index == 'abo'" class="form-control select-filter-index" :v-model="'data'+index" :id="'select-filter-'+ index" :rel="index" :name="'filters['+index+']'">
                  <option v-for="model in filters" required v-bind:value="model.id">{{ model.title }}</option>
               </select>

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

    props: ['search'],
    data () {
        return {
            model: this.search.model ? this.search.model : 'order',
            sum: this.search.sum ? this.search.sum : null,
            group: this.search.group ? this.search.group : null,
            start: '',
            end: '',
            filters:[],
            models:[],
            dataabo : this.search.filters && this.search.filters.abo ? this.search.filters.abo : [],
            datacolloque : this.search.filters && this.search.filters.colloque ? this.search.filters.colloque : [],
            dataauthors : this.search.filters && this.search.filters.authors ? this.search.filters.authors : [],
            datadomains : this.search.filters && this.search.filters.domains ? this.search.filters.domains : [],
            datacategories : this.search.filters && this.search.filters.categories ? this.search.filters.categories : [],
        }
    },
    computed: {
    },
    mounted: function ()  {

       this.getModels();

       this.start = this.search.period && this.search.period.start ? this.search.period.start : '';
       this.end = this.search.period && this.search.period.end ? this.search.period.end : '';

       this.$nextTick(function(){
          let self = this;
          $.fn.datepicker.dates['fr'] = {
             days: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
             daysShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
             daysMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
             months: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
             monthsShort: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
             today: "Aujourd'hui",
             clear: "Clear"
          };

          $('.datePickerStats').datepicker({
             format: 'yyyy-mm-dd',
             language: 'fr'
          }).on('changeDate', function(e) {
             let period =  $(this).data('period');
             let value = $(this).val();

             self.setPeriod(period,value);
          });
       });
    },
    methods: {
       setPeriod(period,value){
          console.log(period);
          console.log(value);

          if(period == 'start'){
             this.start = value;
          }
          if(period == 'end'){
             this.end = value;
          }
       },
       getModels: function() {
          let self = this;

          self.models = [];

          setTimeout(function() {
             $('.select-filter').trigger('chosen:updated');
          },100);

          axios.post('vue/models',{model: self.model}).then(function (response) {

             self.models = response.data;

             setTimeout(function() {
                $('.select-filter-index').chosen();
                $('.select-filter-index').chosen().change(function() {
                   let value = $(this).val();
                   let filter = $(this).attr('rel');
                });
                $('.select-filter').trigger('chosen:updated');
             },100);

          }).catch(function (error) { console.log(error);});

       },
    }
}
</script>