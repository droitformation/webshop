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
           <div>
              <div class="form-group">
                  <label class="control-label" for="reference_no">N° référence</label>
                  <input class="form-control" name="reference_no" id="reference_no" type="text" placeholder="Optionnel">
              </div>
               <div class="form-group">
                   <label class="control-label" for="transaction_no">N° commande</label>
                   <input class="form-control" name="transaction_no" id="transaction_no" type="text" placeholder="Optionnel">
               </div>
           </div>
       </div>
       <address style="position: relative;">

           <div v-if="changed" class="text-success" style="position: absolute;top: 25px;">Adresse mise à jour<i class="fa fa-check"></i></div>
           <div><button @click="open" type="button" class="text-danger" style="padding: 0; margin-bottom: 5px;"><i class="fa fa-undo"></i>
               Changer l'adresse de facturation <i class="fa fa-caret-down"></i></button></div>

           <transition name="slide" mode="out-in">

                <div v-if="change" class="billing-form-update" id="billing-form">
                   <h4>Adresse de facturation <cite class="text-danger pull-right" style="color: red;"><small>* Champs requis</small></cite></h4>
                   <ul v-if="change" class="billing-form">
                       <li class="form-group">
                           <div class="col-md-6">
                               <label class="control-label" for="">Titre</label>
                               <select name="civilite_id" required v-model="facturation.civilite_id" class="form-control">
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
                               <input class="form-control" required v-model="facturation.first_name" name="first_name" id="first_name" type="text">
                           </div>
                           <div class="col-md-6">
                               <label class="control-label" for="">Nom <sup>*</sup></label>
                               <input class="form-control" required v-model="facturation.last_name" name="last_name" id="last_name" type="text">
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
                               <input class="form-control"required v-model="facturation.adresse" id="adresse" name="adresse" type="text">
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

                       <li>
                           <div class="form-group">
                               <div class="col-md-12 text-right">
                                   <hr/>
                                   <input name="type" value="4" type="hidden">
                                   <input name="user_id" :value="livraison.user_id" type="hidden">
                                   <input v-if="(id != main)" name="id" :value="id" type="hidden">

                                   <button v-on:click.prevent="update" type="button" class="btn btn-primary">Mettre à jour</button>
                               </div>
                           </div>
                       </li>
                   </ul>
               </div>
           </transition>
       </address>
   </div>
</template>
<style>
    .slide-enter-active, .slide-leave-active {
        transition: opacity .8s ease-in-out, transform .8s ease-in-out;
    }

    .slide-enter, .slide-leave-to {
        opacity: 0;
        transform: translateY(20px);
    }
</style>
<script>
    export default {
        props: ['livraison','facturation','main'],
        data () {
            return {
                url: location.protocol + "//" + location.host+"/",
                change:false,
                changed:false,
                livraison_detail:'',
                facturation_detail:'',
                id : this.facturation ? this.facturation.id : null,
                adresse_livraison : this.livraison ? this.livraison : null,
                adresse_facturation : this.facturation ? this.facturation : null,
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.$forceUpdate();
            this.fetchLivraison(this.adresse_livraison.id);
            this.fetchFacturation(this.adresse_facturation.id);
        },
        methods:{
            open: function() {
                this.change = this.change ? false :true;

                this.$nextTick(function(){
                    let self = this;

                    $('html, body').animate({
                        scrollTop: $('#billing-form').offset().top
                    }, 600);
                });
            },
            fetchLivraison (id) {
                let self = this;
                axios.get(self.url+ 'admin/adresse/getAdresseDetail/' + id, {}).then(function (response) {
                    //console.log(response.data);
                    self.livraison_detail = response.data;
                }).catch(function (error) { console.log(error);});
            },
            fetchFacturation (id) {
                let self = this;

                axios.get(self.url+'admin/adresse/getAdresseDetail/' + id, {}).then(function (response) {
                   // console.log(response.data);
                    self.facturation_detail = response.data;
                    console.log(self.facturation_detail);
                }).catch(function (error) { console.log(error);});
            },
            update(){
                console.log('update');
                let self = this;
                axios.post(self.url+'admin/adresse/createOrUpdateFacturation', this.facturation).then(function (response) {

                    self.adresse_facturation = response.data;
                    self.fetchFacturation(self.adresse_facturation.id);

                    $('html, body').animate({
                        scrollTop: $('#inscriptionForm').offset().top
                    }, 600);

                    self.change = false;
                    self.changed = true;

                    setTimeout(function() {
                        self.changed = false;
                    },3000);

                }).catch(function (error) { console.log(error);});
            }
        }
    }
</script>
