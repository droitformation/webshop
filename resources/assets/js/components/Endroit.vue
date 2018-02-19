
<template>
   <div>
       <div class="choixAdresse">
           <select class="form-control form-required required" v-model="selected" name="location_id" v-on:change="updateAdresse">
               <option v-for="adresse in list" v-bind:value="adresse.id">{{ adresse.name }}</option>
           </select>
       </div>
       <div class="thumbnail thumbnail-colloque">
            <div class="row">
                <div class="col-md-3" v-html="map"></div>
                <div class="col-md-8">
                    <h4 v-html="name"></h4>
                    <p v-html="adresse"></p>
                </div>
            </div>
       </div>
   </div>
</template>
<style>
    .choixAdresse{
        margin-bottom:10px;
    }
</style>
<script>
export default {

    props: ['endroit','adresses'],
    data () {
        return {
            list: [],
            map: '<span class="text-danger">il n\'existe pas de carte</span>',
            name: '',
            adresse:''
        }
    },
    computed: {
       computedEndroit: function () {
            return this.endroit
        },
    },
    beforeMount: function ()  {
        this.selected = this.endroit;

        this.getAdresses();
        this.updateAdresse();
    },
    methods: {
        getAdresses : function(){
           this.list = this.adresses;
        },
        makeAdresse: function(location){
            this.name    = location.name;
            this.adresse = location.adresse;

            if(location.map){
                this.map = '<img style="max-width:100%;" src="files/colloques/cartes/'+ location.map +'" alt="Carte">';
            }
        },
        updateAdresse : function(){

            var self = this;
            axios.post('/admin/location/colloque', { id: this.selected }).then(function (response) {
               self.makeAdresse(response.data.location);
            }).catch(function (error) { console.log(error);});

        }
    }
}
</script>