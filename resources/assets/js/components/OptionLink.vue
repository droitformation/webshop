<template>
    <div>

        <h4>Choix du prix applicable</h4>
        {{ form }}
        <div class="list_prices">

            <div v-show="!linked" class="price-select">
                <div class="form-group" v-if="prices.length != 0">
                    <label><strong>Prix normal</strong></label>
                    <select name="price_id" class="form-control select-price" @change="select" v-model="normal">
                        <option value="">Choix</option>
                        <option v-for="price in prices" :value="price.id" >{{ price.description }} | {{ price.price }} CHF</option>
                    </select>
                </div>
            </div>

            <div v-show="!normal"  class="price-select">
                <div class="form-group" v-if="pricelinks.length != 0">
                    <label><strong>Prix liés</strong></label>
                    <select name="price_link_id" class="form-control select-price" @change="select" v-model="linked">
                        <option value="">Choix</option>
                        <option v-for="pricelink in pricelinks" :value="pricelink.id" >{{ pricelink.description }} | {{ pricelink.price }} CHF</option>
                    </select>
                </div>
            </div>

            <a href="#" class="text-danger" @click.prevent="show" type="button" v-if="chosed">changer</a>
        </div>

        <h4>Merci de préciser les options</h4>

        <option-list :typeform="typeform" type="normal" :colloque="colloque" :inValidation="inValidation" @validated="handleValidated" :options="options"></option-list>

        <div v-if="priceoptions.length != 0" v-for="priceoption in priceoptions">
            <option-list :typeform="typeform" type="link" :colloque="priceoption.colloque" :inValidation="inValidation" @validated="handleValidated" :options="priceoption.options"></option-list>
        </div>

        <div class="form-group" v-if="form == 'simple'">
            <br><button id="makeInscription" class="btn btn-danger pull-right" @click="validate($event)" type="submit">Inscrire</button>
        </div>
    </div>
</template>
<style>
    .price-select{
        width: 100%;
    }
</style>
<script>
    import OptionList from './OptionList.vue';

    export default {
        props: ['colloque','prices','pricelinks','colloques','form'],
        components:{
            'option-list' : OptionList
        },
        data () {
            return {
                options:[],
                priceoptions:[],
                chosed:false,
                linked:'',
                normal:'',
                inValidation:false,
                isValid:false,
                typeform: this.form == 'multiple' ? 'multiple' : 'simple'
            }
        },
        mounted: function () {
            this.getAll();
        },
        watch: {
            linked: function (id) {
                this.getOptions(id);
            },
        },
        methods: {
            show(){
                this.linked = '';
                this.normal = '';

                $('div[class="price-select"]').show();
                this.chosed = false;
            },
            getOptions(id){
                var self = this;
                axios.get('/vue/priceoptions/' + id + '/' + this.colloque.id, {}).then(function (response) {
                    self.priceoptions = response.data;
                }).catch(function (error) { console.log(error);});
            },
            select(){
                this.chosed = true;
            },
            getAll:function(){
                var self = this;
                axios.get('/vue/options/' + this.colloque.id, {}).then(function (response) {
                    self.options = response.data;
                }).catch(function (error) { console.log(error);});
            },
            validate(event){
                this.inValidation = true;

                if(!this.linked && !this.normal){
                    event.preventDefault();
                    alert('Merci de choisir un prix');
                }

                if(!this.isValid){
                    event.preventDefault();
                    alert('Merci de choisir une option');
                }
            },
            handleValidated(event){
                this.isValid = event;
            }
        }
    }
</script>
