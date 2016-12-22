<template>
    <div>

        <p><a @click="addInscription" class="btn btn-sm btn-info"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>

        <fieldset class="inscription-item" v-for="inscription in inscriptions">

            <div class="form-group">
                <label>Nom du participant</label>
                <input v-model="inscription.participant" required class="form-control"  type="text">
            </div>
            <div class="form-group">
                <h4>Choix du prix applicable</h4>
                <select class="form-control form-required required" v-model="inscription.price_id">
                    <option value="">Choix</option>
                    <option v-for="price in list_prices" required v-bind:value="price.id">{{ price.description }} | {{ price.price }} CHF</option>
                </select>
            </div>
            <div class="form-group">
                <h4>Conférences</h4>

                <div class="inscription-item-choix" v-for="occurrence in list_occurrences">
                    <label>
                        <input type="checkbox" v-bind:value="occurrence.id" required v-model="inscription.occurrences"> {{ occurrence.title }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h4>Options</h4>

                <div v-for="option in inscription.options">

                    <div v-if="option.type == 'checkbox'" class="inscription-item-choix">
                        <label><input @change="pushOption(option)" type="checkbox" v-bind:value="option.id" v-model="option.checked"> {{ option.title }}</label>
                    </div>

                    <div v-if="option.type == 'choix' && option.groupe.length != 0">
                        <h4>Choix</h4>
                        <div class="inscription-item-choix">
                            <p><strong>{{ option.title }}</strong></p>
                            <label v-for="group in option.groupe">
                                <input type="radio" name="groupe_id" required :value="group.id" v-model="option.checked"> {{ group.text }}
                            </label>
                        </div>
                    </div>

                    <div v-if="option.type == 'text'" class="inscription-item-choix">
                        <p><strong>{{ option.title }}</strong></p>
                        <label><textarea class="form-control" required v-model="option.reponse"></textarea></label>
                    </div>

                </div>

            </div>
        </fieldset>
        {{ inscriptions }}
    </div>
</template>
<style>
</style>
<script>

    export default{
        props: ['colloque','options','occurrences','prices','user'],
        data(){
            return{
                count: 0,
                list_options: [],
                list_occurrences: [],
                list_prices: [],
                inscriptions: []
            }
        },
        beforeMount: function () {
             this.getData();
        },
        mounted: function () {

        },
        methods: {
            getData : function(){

                this.list_prices = this.prices;
                this.list_occurrences = this.occurrences;

                this.list_options = _.orderBy(this.options, ['type'],['desc']);
                this.addInscription();
            },
            pushOption : function(option){

            },
            addInscription: function() {
               var options = [];

                $.each( this.list_options, function( index, value ){

                    var groupe = [];

                    $.each( value.groupe, function( index, value_groupe ){
                        groupe.push({
                            id  : value_groupe.id,
                            text: value_groupe.text
                        });
                    });

                    options.push({
                        type: value.type,
                        title: value.title,
                        id: value.id,
                        checked: false,
                        reponse: '',
                        groupe: groupe
                    });
                });

                this.inscriptions.push({
                    user_id: this.user.id,
                    colloque_id: this.colloque.id,
                    participant: '',
                    price_id: '',
                    type: 'multiple',
                    occurrences: [],
                    options: options
                });
            },
         }
    }
</script>
