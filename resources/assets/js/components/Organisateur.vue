
<template>
   <div>
       <div class="choixAdresse">
           <select class="form-control form-required required" v-model="selected" name="adresse_id" v-on:change="updateAdresse">
               <option v-for="adresse in list" v-bind:value="adresse.id">{{ adresse.name }}</option>
           </select>
       </div>
       <div class="thumbnail thumbnail-colloque">
            <div class="row">
                <div class="col-md-3" v-html="logo"></div>
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

    props: ['organisateur','adresses'],
    data () {
        return {
            list: [],
            logo: '<span class="text-danger">il n\'existe pas de logo</span>',
            name: '',
            adresse:''
        }
    },
    computed: {
       computedEndroit: function () {
            return this.organisateur
        },
    },
    beforeMount: function ()  {
        this.selected = this.organisateur;

        this.getAdresses();
        this.updateAdresse();
    },
    methods: {
        getAdresses : function(){
           this.list = this.adresses;
        },
        makeAdresse: function(organisateur){
            this.name    = organisateur.name;
            this.adresse = organisateur.adresse;

            if(organisateur.logo)
            {
                this.logo = '<img style="max-width:100%;max-height:100px;" src="files/logos/'+ organisateur.logo +'" alt="Logo">';
            }
        },
        updateAdresse : function(){

            var self = this;
            axios.post('/admin/organisateur/colloque', { id: this.selected }).then(function (response) {
                 self.makeAdresse(response.data.organisateur);
            }).catch(function (error) { console.log(error);});

        }
    }
}
</script>