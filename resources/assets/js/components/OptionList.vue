<template>
    <div id="forms">
        <p class="option-title">{{ colloque.titre }}</p>
        <div v-if="empty(options)">Pas d'options</div>

        <div v-for="(option,index) in options">

            <div v-if="option.type == 'checkbox'">
                <div class="form-group type-choix" >
                    <input type="checkbox" class="option-input" :checked="option.state ? 'checked' : ''" checked v-model="option.state" :name="checkbox(index)" :value="option.id">&nbsp;{{ option.title }}
                </div>
            </div>

            <div v-if="option.type == 'choix'">
                <div class="form-group group-choix type-choix">
                    <label class="control-label"><strong>{{ option.title }}</strong></label>
                    <div v-if="option.groupe.length != 0" class="radio" v-for="groupe in option.groupe">
                        <label><input type="radio" required class="group-input" :name="radio(option)" :value="groupe.id">{{ groupe.text }}</label>
                    </div>
                </div>
            </div>

            <div v-if="option.type == 'text'">
                <div class="form-group type-choix">
                    <label><strong>{{ option.title }}</strong></label>
                    <textarea class="form-control text-input" :name="textarea(option)"></textarea>
                </div>
            </div>
        </div>
      <input type="hidden" style="height: 1px;line-height: 1px;" :name="normal" />&nbsp;
    </div>
</template>
<script>
    export default {
        props: ['options', 'colloque', 'type', 'form', 'participant_id'],
        data() {
            return {
                isValide:false
            }
        },
        mounted: function () {},
        watch: {
            inValidation: function (val) {
                if(this.inValidation){
                    this.validate();
                }
            },
        },
        computed: {
            normal(){
              return this.form == 'multiple' ? 'addons['+this.colloque.id+'][options]['+this.participant_id+']' : 'colloques['+this.colloque.id+'][options]' ;
            },
        },
        methods: {
            empty(obj){
              return Object.keys(obj).length === 0;
            },
            checkbox: function (index) {
                return this.form == 'multiple' ? 'addons['+this.colloque.id+'][options]['+this.participant_id+'][]' : 'colloques['+this.colloque.id+'][options]['+index+']' ;
            },
            radio: function (option) {
                return this.form == 'multiple' ? 'addons['+this.colloque.id+'][groupes]['+this.participant_id+']['+ option.id +']' : 'colloques['+this.colloque.id+'][groupes]['+ option.id +']';
            },
            textarea:function(option){
                return this.form == 'multiple' ? 'addons['+this.colloque.id+'][options]['+this.participant_id+'][]['+ option.id +']' : 'colloques['+this.colloque.id+'][options][]['+ option.id +']' ;
            },
            validate: function () {
                let $radios = $('div.group-choix');
                let data = [];

                $radios.each(function(groupe){
                    let checked = $(this).find('input[type="radio"]:checked').val();
                    if(checked){data.push(checked);}
                });

                if(data.length == $radios.length){
                    this.isValide = true;
                }
            }
        }
    }
</script>
<style scoped>
    .option-title{
        margin:15px 0 10px 0;
        font-size: 18px;
        color:#1e4b78;
        font-weight:bold;
    }
</style>