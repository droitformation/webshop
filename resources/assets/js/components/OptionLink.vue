<template>
    <div>
        <h4>Choix du prix applicable</h4>
        <div class="list_prices">
            <div class="form-group" v-if="prices.length != 0">
                <label><strong>Prix normal</strong></label>
                <select name="price_id" class="form-control">
                    <option>Choix</option>
                    <option v-for="price in prices" :value="price.id" >{{ price.description }} | {{ price.price_cents }} CHF</option>
                </select>
            </div>

            <div class="form-group" v-if="pricelinks.length != 0">
                <label><strong>Prix liés</strong></label>
                <select name="price_link_id" class="form-control">
                    <option>Choix</option>
                    <option v-for="pricelink in pricelinks" :value="pricelink.id" >{{ pricelink.description }} | {{ pricelink.price_cents }} CHF</option>
                </select>
            </div>
        </div>

        <h4>Merci de préciser</h4>
        <div v-for="(option,index) in options">
            <div v-if="option.type == 'checkbox'">
                <div class="form-group type-choix" >
                    <input type="checkbox" class="option-input" :name="option.type == 'multiple' ? 'options[0][]':'options['+index+']' " :value="option.id" /> &nbsp;{{ option.title }}
                </div>
            </div>
            <div v-if="option.type == 'choix'">
                <div class="form-group group-choix type-choix">
                    <label class="control-label"><strong>{{ option.title }}</strong></label>
                    <div v-if="option.groupe.length != 0" class="radio" v-for="groupe in option.groupe">
                        <label><input type="radio" required class="group-input" :name="'groupes['+ option.id +']'" :value="groupe.id">{{ groupe.text }}</label>
                    </div>
                </div>
            </div>
            <div v-if="option.type == 'text'">
                <div class="form-group type-choix">
                    <label><strong>{{ option.title }}</strong></label>
                    <textarea class="form-control text-input" :name="'options[]['+ option +']'"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['colloque','prices','pricelinks'],
        data () {
            return {
                options:[]
            }
        },
        mounted: function () {
            this.getAll();
        },
        methods: {
            getAll:function(){
                var self = this;
                axios.get('/vue/options/' + this.colloque, {}).then(function (response) {
                    self.options = response.data;
                }).catch(function (error) { console.log(error);});
            },
        }
    }
</script>
