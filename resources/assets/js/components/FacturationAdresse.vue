<template>
   <div>
       <div class="adresse-verify">
           <address id="userAdresse">
               <h5>Adresse indiqué sur bon</h5>
               <div v-html="livraison_detail"></div>
           </address>
           <address id="userFacturation">
               <h5>Adresse indiqué sur facture</h5>
               <div v-html="facturation_detail"></div>
           </address>
       </div>
       <address>
           <p><button @click="open" type="button" class="text-danger">Changer l'adresse de facturation <i class="fa fa-caret-down"></i></button></p>
           <ul v-show="change" class="billing-form">
               <i><h4>Adresse de facturation</h4></i>
               <li class="form-group">
                   <div class="col-md-6">
                       <label class="control-label" for="">Titre</label>
                       <select name="civilite_id" v-model="facturation.civilite_id" class="form-control">
                           <option value="4"></option>
                           <option value="1">Monsieur</option>
                           <option value="2">Madame</option>
                           <option value="3">Me</option>
                       </select>
                   </div>
               </li>
               <li class="form-group">
                   <div class="col-md-6">
                       <label class="control-label" for="">Prénom <sup>*</sup></label>
                       <input class="form-control" v-model="facturation.first_name" name="first_name" id="first_name" type="text">
                   </div>
                   <div class="col-md-6">
                       <label class="control-label" for="">Nom <sup>*</sup></label>
                       <input class="form-control" v-model="facturation.last_name" name="last_name" id="last_name" type="text">
                   </div>
               </li>
               <li class="form-group">
                   <div class="col-md-6">
                       <label class="control-label" for="">Entreprise</label>
                       <input class="form-control" v-model="facturation.company"  name="company" type="text">
                   </div>
               </li>

               <li class="form-group">
                   <div class="col-md-12">
                       <label class="control-label" for="">Adresse <sup>*</sup></label>
                       <input class="form-control" v-model="facturation.adresse" id="adresse" name="adresse" type="text">
                   </div>
               </li>
               <li class="form-group">
                   <div class="col-md-4">
                       <label class="control-label" for="">CP</label>
                       <input class="form-control" v-model="facturation.cp" id="cp" name="cp" type="text">
                   </div>
                   <div class="col-md-8">
                       <label class="control-label" for="">Complément d'adresse</label>
                       <input class="form-control" v-model="facturation.complement" id="complement" name="complement" type="text">
                   </div>
               </li>
               <li class="form-group">
                   <div class="col-md-4">
                       <label class="control-label" for="">NPA <sup>*</sup></label>
                       <input class="form-control" v-model="facturation.npa" id="npa" name="npa" type="text">
                   </div>
                   <div class="col-md-8">
                       <label class="control-label" for="">Ville <sup>*</sup></label>
                       <input class="form-control" v-model="facturation.ville" id="ville" name="ville" type="text">
                   </div>
               </li>
               <li class="form-group">
                   <div class="col-md-6">
                       <label class="control-label" for="">Pays</label>
                       <select disabled name="pays_id" v-model="facturation.pays_id" class="form-control">
                           <option value="208">Suisse</option>
                       </select>
                   </div>
               </li>
               <li class="form-group"><div class="col-md-12"><hr/></div></li>
               <li class="form-group">
                   <div class="col-md-6">
                       <label class="control-label" for="">Votre référence</label>
                       <input class="form-control" v-model="reference_no" id="reference_no" name="reference_no" type="text">
                   </div>
                   <div class="col-md-6">
                       <label class="control-label" for="">N° de commande</label>
                       <input class="form-control" v-model="transaction_no" id="transaction_no" name="transaction_no" type="text">
                   </div>
               </li>

               <li>
                   <div class="form-group">
                       <div class="col-md-12">
                           <hr/>
                           <input name="type" value="4" type="hidden">
                           <input name="user_id" value="facturation.user_id" type="hidden">
                           <input v-if="facturation" name="id" value="facturation.id" type="hidden">

                           <cite class="text-danger"><small>* Champs requis</small></cite>
                           <button @click="update" type="button" class="btn btn-info">Envoyer</button>
                       </div>
                   </div>
               </li>
           </ul>

       </address>
   </div>
</template>

<script>
    export default {
        props: ['livraison','facturation'],
        data () {
            return {
                url: location.protocol + "//" + location.host+"/",
                change:false,
                reference_no:null,
                transaction_no:null,
                livraison_detail:'',
                facturation_detail:'',
                adresse_livraison : this.livraison ? this.livraison : null,
                adresse_facturation : this.facturation ? this.facturation : null,
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.fetchLivraison(this.adresse_livraison.id);
            this.fetchFacturation(this.adresse_facturation.id);
        },
        methods:{
            open: function() {
                this.change = this.change ? false :true;
            },
            fetchLivraison (id) {
                var self = this;
                axios.get(self.url+ 'admin/adresse/getAdresseDetail/' + id, {}).then(function (response) {
                   // console.log(response.data);
                    self.livraison_detail = response.data;
                }).catch(function (error) { console.log(error);});
            },
            fetchFacturation (id) {
                var self = this;
                console.log(this.adresse_facturation);
                axios.get(self.url+'admin/adresse/getAdresseDetail/' + id, {}).then(function (response) {
                    console.log(response.data);
                    self.facturation_detail = response.data;
                }).catch(function (error) { console.log(error);});
            },
            update(){
                console.log(this.adresse_facturation);

            }
        }
    }
</script>
