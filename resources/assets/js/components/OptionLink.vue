<template>
    <div>

        <h4>Choix du prix applicable</h4>

        <div class="list_prices">

            <div class="price-select">
                <div class="form-group" v-if="prices.length != 0">
                    <label><strong>Prix</strong></label>
                    <select class="form-control select-price" @change="select($event)" v-model="prix">
                        <option value="">Choix</option>
                        <option v-for="price in prices" :value="price">{{ price.description }} | {{ price.price }} CHF</option>
                        <option v-if="pricelinks.length != 0" v-for="pricelink in pricelinks" :value="pricelink">{{ pricelink.description }} | {{ pricelink.price }} CHF</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-if="priceoptions.length != 0" v-for="priceoption in priceoptions">
            <h4>Merci de préciser les options</h4>
            <option-list :participant_id="participant_id" :form="form" :colloque="priceoption.colloque" :options="priceoption.options"></option-list>
        </div>

        <div v-if="prix">
            <input v-if="form == 'multiple'" v-for="colloque in prix.linked" :name="'colloques['+ participant_id +'][]'" type="hidden" :value="colloque.id">
            <input v-if="form == 'multiple'" :name="'price_id['+ participant_id +']'" :value="prix.genre+':'+prix.id" type="hidden">
            <input v-if="form == 'simple'" name="price_id" :value="prix.genre+':'+prix.id" type="hidden">
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
        props: ['colloque','prices','pricelinks','participant_id','form'],
        components:{
            'option-list' : OptionList
        },
        data () {
            return {
                options:[],
                priceoptions:[],
                choose:false,
                type:'normal',
                prix:null,
                isValid:false,
            }
        },
        mounted: function () {},
        watch: {
            prix: function (value) {
                this.getOptions();
            },
        },
        methods: {
            getOptions(){
                var self = this;
                axios.post('/vue/options',{
                    price : this.prix
                }).then(function (response) {
                    self.priceoptions = response.data;
                }).catch(function (error) { console.log(error);});
            },
            select($event){
                console.log($event.target.value);
                if ($event.target.options.selectedIndex > -1) {
                    const theTarget = $event.target.options[$event.target.options.selectedIndex].dataset;
                    this.type = theTarget.type
                }
            },
        }
    }
</script>
