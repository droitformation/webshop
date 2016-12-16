
<template>
   <div>
       <ul class="list-group">
           <li v-for="(index,occurrence) in list" class="list-group-item">
               <div class="btn-group pull-right">
                    <a @click="edit(index)" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                    <a @click="delete(index)" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
               </div>
               <div class="row">
                   <div class="col-md-8">
                       <div class="form-group-item">
                           <p><strong>Titre</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.title }}</p>
                           <p v-if="occurrence.state"><input class="form-control" name="title" type="text" v-bind::value="occurrence.title"></p>
                       </div>
                       <div class="form-group-item">
                           <p><strong>Lieu</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.lieux }}</p>
                           <p v-if="occurrence.state">
                               <select class="form-control form-required required" name="lieux_id">
                                   <option value="">Choix</option>
                                   <option v-for="location in loc" v-bind::value="location.id">{{ location.name }}</option>
                               </select>
                           </p>
                       </div>
                   </div>
                   <div class="col-md-3">
                       <div class="form-group-item">
                           <p><strong>Date</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.start }}</p>
                           <p v-if="occurrence.state">
                               <input name="starting_at" class="form-control datePickerApp" v-bind:value="occurrence.start">
                           </p>
                       </div>
                       <div class="form-group-item">
                           <p><strong>Capacité</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.capacite_salle }}</p>
                           <p v-if="occurrence.state"><input class="form-control" name="capacite_salle" type="text" v-bind::value="occurrence.capacite_salle"></p>
                       </div>
                   </div>
               </div>
           </li>
       </ul>

   </div>
</template>
<style>

</style>
<script>

export default {

    props: ['occurrences','locations'],
    data () {
        return {
            list: [],
            loc: []
        }
    },
    beforeMount: function ()  {
        this.getOccurrences();
        this.getLocations();
    },
    methods: {
        edit : function(occurence){

            this.list[occurence.id].state = true;
            console.log(this.list);
           // this.occurrence.state = true;
           // this.objects[id].done = true;
        },
        getOccurrences : function(){

           this.list = this.occurrences;
            console.log(this.list);
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

                $('.datePickerApp').datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'fr'
                });
            });
        },
        getLocations : function(){
           this.loc = this.locations;
        }
    }
}
</script>