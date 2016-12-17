
<template>
   <div>
       <p class="text-right"><a @click="ajouter" class="btn btn-sm btn-success">Ajouter</a></p>
       <ul class="list-group">
           <li class="list-group-item" id="addOccurrence" v-show="add">
                <div class="row">
                    <div class="col-md-8">
                       <div class="form-group-item">
                           <p><strong>Titre</strong></p>
                           <p><input class="form-control" name="title" type="text" v-model="nouveau.title"></p>
                       </div>
                       <div class="form-group-item">
                           <p><strong>Lieu</strong></p>
                           <p>
                               <select class="form-control form-required required" v-model="nouveau.lieux_id" name="lieux_id">
                                   <option value="">Choix</option>
                                   <option v-for="location in loc" v-bind:value="location.id">{{ location.name }}</option>
                               </select>
                           </p>
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group-item">
                           <p><strong>Date</strong></p>
                           <p><input name="starting_at" class="form-control datePickerNew" v-model="nouveau.starting_at"></p>
                       </div>
                       <div class="form-group-item">
                           <p><strong>Capacité</strong></p>
                           <p><input class="form-control" name="capacite_salle" v-model="nouveau.capacite_salle" type="text"></p>
                       </div>
                    </div>
                </div>
                <p class="text-right margBottom"><a @click="ajouterOccurence" class="btn btn-sm btn-primary">Envoyer</a></p>
           </li>
           <li v-for="occurrence in list" class="list-group-item">
               <div class="row">
                   <div class="col-md-12">
                       <div class="btn-group pull-right">
                            <a v-show="!occurrence.state" @click="edit(occurrence)" class="btn btn-xs btn-info">éditer</a>
                            <a v-show="!occurrence.state" @click="deleteOccurrence(occurrence)" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
                            <a v-show="occurrence.state" @click="save(occurrence)" class="btn btn-xs btn-primary">sauvegarder</a>
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-md-8">
                       <div class="form-group-item">
                           <p><strong>Titre</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.title }}</p>
                           <p v-if="occurrence.state"><input class="form-control" name="title" type="text" v-model="occurrence.title" v-bind:value="occurrence.title"></p>
                       </div>
                       <div class="form-group-item">
                           <p><strong>Lieu</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.lieux }}</p>
                           <p v-if="occurrence.state">
                               <select class="form-control form-required required" v-model="occurrence.lieux_id" name="lieux_id">
                                   <option value="">Choix</option>
                                   <option v-for="location in loc"
                                           v-bind:selected="occurrence.lieux_id == location.id ? 'true' : 'false'"
                                           v-bind:value="location.id">
                                       {{ location.name }}
                                   </option>
                               </select>
                           </p>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="form-group-item">
                           <p><strong>Date</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.starting_at }}</p>
                           <p v-if="occurrence.state">
                               <input name="starting_at" class="form-control datePickerApp" v-model="occurrence.starting_at" v-bind:value="occurrence.starting_at">
                           </p>
                       </div>
                       <div class="form-group-item">
                           <p><strong>Capacité</strong></p>
                           <p v-if="!occurrence.state">{{ occurrence.capacite_salle }}</p>
                           <p v-if="occurrence.state"><input class="form-control" name="capacite_salle" v-model="occurrence.capacite_salle" type="text" v-bind:value="occurrence.capacite_salle"></p>
                       </div>
                   </div>
               </div>
           </li>
       </ul>
   </div>
</template>
<style>
    #addOccurrence{
        margin-bottom:15px;
    }
    .margBottom{
      padding-bottom:5px;
    }
</style>
<script>

export default {

    props: ['occurrences','locations','colloque'],
    data () {
        return {
            list: [],
            loc : [],
            nouveau:{
                title: '',
                lieux_id: '',
                starting_at: '',
                capacite_salle: '',
                colloque_id: this.colloque
            },
            add : false
        }
    },
    beforeMount: function ()  {
        this.getOccurrences();
        this.getLocations();
    },
    methods: {
        edit : function(occurence){
            this.list[occurence.id].state = true;
        },
        delete : function(occurence){
            this.list[occurence.id].state = false;
        },
        getOccurrences : function(){

           this.list = this.occurrences;

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

                var self = this;

                $('.datePickerNew').datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'fr'
                }).on('changeDate', function(ev){
                   self.nouveau.starting_at = ev.target.value;
                });
            });
        },
        getLocations : function(){
           this.loc = this.locations;
        },
        updateOccurrences:function(occurrences){
            this.list = occurrences;
        },
        ajouterOccurence:function(){

            this.$http.post('/vue/occurrence', { occurrence : this.nouveau }).then((response) => {

                this.updateOccurrences(response.body.occurrences);
                this.add = false;

            }, (response) => {
            // error callback
            }).bind(this);
        },
        ajouter:function(){
            this.add = true;
        },
        save : function(occurence){

            var model = this.list[occurence.id];

            this.$http.post('/vue/occurrence/' + model.id, { model, '_method' : 'put'  }).then((response) => {

                this.updateOccurrences(response.body.occurrences);

            }, (response) => {
            // error callback
            }).bind(this);

        },
        deleteOccurrence :function(occurence){

            var model = this.list[occurence.id];

            this.$http.post('/vue/occurrence/' + model.id, { '_method' : 'DELETE' }).then((response) => {

                this.updateOccurrences(response.body.occurrences);

            }, (response) => {
            // error callback
            }).bind(this);
        },
    }
}
</script>