<template>

    <div class="well">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="col-sm-3 control-label"><strong>Type d'envoi</strong></label>
            <div class="col-sm-8">
                <label class="radio">
                    <input type="radio" name="static" v-model="static" value="0" @change="isStatic()"> Liste externe, emails exportés depuis le système
                </label>
                <label class="radio">
                    <input type="radio" name="static" v-model="static" value="1" @change="isStatic()"> Liste statique (Comme bail, Droitmatrimonial...) lié à un/des tag(s)
                </label>
            </div>
        </div>

        <div class="form-group" v-show="mailjet" style="margin-bottom: 20px; margin-top: 20px;">
            <label class="col-sm-3 control-label"><strong>Spécialisations (tags)</strong></label>
            <div class="col-sm-5">
                <select class="specialisations-list" v-model="tags" name="specialisations[]" multiple="multiple" style="width: 100%;">
                    <option :selected="isSelected(specialisation.id)" v-for="specialisation in specialisations" v-bind:value="specialisation.id">{{ specialisation.title }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Nom de la liste</label>
        <div class="col-sm-5">
            <select class="form-control" v-model="list_id" name="list_id">
                <option value="">Choix de la liste</option>
                <option v-for="list in liste" v-bind:value="list.ID">{{ list.Name] }}</option>
            </select>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['specialisations','tags','static','list_id','liste'],
        data () {
            return {
                visible:false,
                options: [
                    'foo',
                    'bar',
                    'baz'
                ],
            }
        },
        computed: {
            mailjet: function () {
                return this.static == 1 ? true : false;
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.initialize();
            this.isStatic();
        },
        methods: {
            initialize(){

                this.$nextTick(function(){
                    var self = this;

                    $('.specialisations-list').select2({
                        placeholder: 'Spécialisations'
                    });
                });

            },
            isStatic : function(){
                this.visible = this.static == 1 ? true : false;
            },
            isSelected(id){
                return _.includes(this.tags,id) ? 'selected' : '';
            }
        }
    }
</script>
