<template>
    <div class="adresse_update">

        <div v-if="showing">
            <h5 v-if="title"><div v-html="title"></div></h5>
            <div class="adresse-wrapper" v-html="update_detail"></div>
        </div>

        <div :class="'text-' + direction">
            <button type="button" @click="show" :class="'btn ' + btnClass">{{ btnText }}</button>
            <button class="btn btn-danger btn-sm" v-on:click.prevent="remove" v-show="type == 5 && admin == 1">x</button>
        </div>

        <modal :name="'update-modal'+id" :adaptive="true" :scrollable="true" :reset="true" height="auto">

            <div class="adresse-update-wrapper">
                <h4><div v-html="title"></div> <cite class="text-danger pull-right" style="color: red;"><small>* Champs requis</small></cite></h4>
                <div class="adresse-update">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="">Titre</label>
                            <select name="civilite_id" required v-model="adresse_update.civilite_id" class="form-control" style="margin-bottom: 10px;">
                                <option value="4"></option>
                                <option value="1">Monsieur</option>
                                <option value="2">Madame</option>
                                <option value="3">Me</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="">Prénom <sup>*</sup></label>
                            <input class="form-control" required v-model="adresse_update.first_name" name="first_name" id="first_name" type="text">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="">Nom <sup>*</sup></label>
                            <input class="form-control" required v-model="adresse_update.last_name" name="last_name" id="last_name" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="">Entreprise</label>
                            <input class="form-control" v-model="adresse_update.company"  name="company" type="text">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="">Adresse <sup>*</sup></label>
                            <input class="form-control"required v-model="adresse_update.adresse" id="adresse" name="adresse" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="">CP</label>
                            <input class="form-control" v-model="adresse_update.cp" id="cp" name="cp" type="text">
                        </div>
                        <div class="col-md-8">
                            <label class="control-label" for="">Complément d'adresse</label>
                            <input class="form-control" v-model="adresse_update.complement" id="complement" name="complement" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="">NPA <sup>*</sup></label>
                            <input class="form-control" v-model="adresse_update.npa" id="npa" name="npa" type="text">
                        </div>
                        <div class="col-md-8">
                            <label class="control-label" for="">Ville <sup>*</sup></label>
                            <input class="form-control" v-model="adresse_update.ville" id="ville" name="ville" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="">Pays</label>
                            <select disabled name="pays_id" v-model="adresse_update.pays_id" class="form-control">
                                <option value="208">Suisse</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-md-6"><hr/>
                            <button v-on:click.prevent="hide" type="button" class="btn btn-default">Annuler</button>
                        </div>
                        <div class="col-md-6 text-right"><hr/>
                            <button v-on:click.prevent="update" type="button" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </div>
                </div>

            </div>

        </modal>
    </div>
</template>
<style>
    .adresse-update{
        padding: 0px;
        background: #fff;
    }

    .adresse-update-wrapper{
        padding: 35px;
    }

    .adresse_update{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
    }

</style>
<script>
    export default {
        props: ['main','original','type','title','dir','hidden','btn','texte','admin','abo_id'],
        components: {},
        data () {
            return {
                url: location.protocol + "//" + location.host+"/",
                change:false,
                btnClass: this.btn ? this.btn : 'btn-warning btn-xs',
                btnText: this.texte ? this.texte : 'Mettre à jour',
                direction: this.dir ? this.dir : 'right',
                showing: this.hidden ? false : true,
                changed:false,
                id:Math.random(),
                update_detail:'',
                adresse_update : this.original ? this.original : null,
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.$forceUpdate();
            this.fetch(this.adresse_update.id);
            this.adresse_update.type = this.type;
        },
        computed: {
            path: function () {
                if(this.type == 4 || this.type == 5){
                    return this.url+'vue/adresse/createOrUpdateFacturation';
                }
                return this.url+'vue/adresse/updateAdresse';
            },
            isDefault: function () {
                return this.main.id == this.original.id;
            }
        },
        methods: {
            show () {
                this.$modal.show('update-modal'+this.id);
            },
            hide () {
                this.$modal.hide('update-modal'+this.id);
            },
            fetch (id) {
                let self = this;

                axios.get(self.url+'vue/adresse/getAdresseDetail/' + id, {}).then(function (response) {
                    self.update_detail = response.data;
                    console.log(self.update_detail);
                }).catch(function (error) { console.log(error);});
            },
            update(){
                let self = this;
                axios.post(this.path, this.adresse_update).then(function (response) {

                    self.adresse_update = response.data;
                    self.fetch(self.adresse_update.id);
                    self.hide();

                }).catch(function (error) { console.log(error);});
            },
            remove(){
                let self = this;
                axios.post(this.url + "vue/adresse/deleteAdresse", {id : this.adresse_update.id, abo_id : this.abo_id}).then(function (response) {

                    if(response.data.result == true){
                        location.reload();
                    }

                }).catch(function (error) { console.log(error);});
            },
        }
    }
</script>
