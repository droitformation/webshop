<template>
    <div>

        <fieldset class="inscription-item" v-for="inscription in inscriptions">

            <div class="form-group">
                <label>Nom du participant</label>
                <input v-model="inscription.participant" required class="form-control"  type="text">
            </div>
            <div class="form-group">
                <h4>Choix du prix applicable</h4>
                <select class="form-control form-required required" v-model="inscription.price_id">
                    <option value="">Choix</option>
                    <option v-for="price in list_prices" v-bind:value="price.id">{{ price.description }} | {{ price.price }} CHF</option>
                </select>
            </div>
            <div class="form-group">
                <h4>Conférences</h4>

                <div class="inscription-item-choix" v-for="occurrence in list_occurrences">
                    <label>
                        <input type="checkbox" v-bind:value="occurrence.id" v-model="inscription.occurrences"> {{ occurrence.title }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h4>Options</h4>

                <div v-for="option in list_options">

                    <div v-if="option.type == 'checkbox'" class="inscription-item-choix">
                        <label>
                            <input type="checkbox" v-bind:value="option.id" v-model="inscription.options"> {{ option.title }}
                        </label>
                    </div>

                    <div v-if="option.type == 'choix' && option.groupe.length != 0">
                        <h4>Choix</h4>
                        <div class="inscription-item-choix">
                            <p><strong>{{ option.title }}</strong></p>
                            <label v-for="(groupe,index) in option.groupe">
                                <input type="radio" v-bind:value="index" v-model="inscription.options"> {{ groupe.text }}
                            </label>
                        </div>
                    </div>

                    <div v-if="option.type == 'text'" class="inscription-item-choix">
                        <p><strong>{{ option.title }}</strong></p>
                        <label>
                            <textarea class="form-control" v-bind:value="option.id" v-model="inscription.options"></textarea>
                        </label>
                    </div>

                </div>

            </div>
        </fieldset>

    </div>
</template>
<style>
</style>
<script>

    export default{
        props: ['colloque','options','occurrences','prices','user'],
        data(){
            return{
                list_options: [],
                list_occurrences: [],
                list_prices: [],
                inscriptions: [
                    {
                        user_id: this.user.id,
                        colloque_id: this.colloque.id,
                        participant: '',
                        price_id: '',
                        occurrences: [],
                        options: null,
                        groupes: [],
                        type: 'multiple'
                    }
                ]
            }
        },
        beforeMount: function () {
            this.getData();
        },
        methods: {
            getData : function(){
                this.list_prices = this.prices;
                this.list_occurrences = this.occurrences;
                this.list_options = this.options;
            },
         }
    }
</script>
