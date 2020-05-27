<template>
    <div>
        {{ typeform }}
        <p class="option-title">{{ colloque.titre }}</p>
        <div v-for="(option,index) in options">
            <div v-if="option.type == 'checkbox'">
                <div class="form-group type-choix" >
                    <input type="checkbox" class="option-input" :name="checkbox(index)" :value="option.id" />&nbsp;{{ option.title }}
                </div>
            </div>
            <div v-if="option.type == 'choix'">
                <div class="form-group group-choix type-choix">
                    <label class="control-label"><strong>{{ option.title }}</strong></label>
                    <div v-if="option.groupe.length != 0" class="radio" v-for="groupe in option.groupe">
                        <label><input type="radio" @change="validate" required class="group-input" :name="radio(option)" :value="groupe.id">{{ groupe.text }}</label>
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
    </div>
</template>
<script>
    export default {
        props: ['options', 'colloque', 'inValidation', 'type', 'typeform'],
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
        methods: {
            checkbox: function (index) {
                return this.isValide == 'multiple' ? 'colloque['+this.this.colloque.id+'][][options][0][]' : 'colloque['+this.colloque.id+'][options]['+index+']' ;
            },
            radio: function (option) {
                return this.isValide == 'multiple' ? 'colloque['+this.colloque.id+'][][groupes][0]['+ option.id +']' : 'colloque['+this.colloque.id+'][groupes]['+ option.id +']';
            },
            textarea:function(option){
                return this.isValide == 'multiple' ? 'colloque['+this.colloque.id+'][][options][0][]['+ option.id +']' : 'colloque['+this.colloque.id+'][options][]['+ option.id +']' ;
            },
            validate: function () {
                let $radios = $('div.group-choix');
                let data = [];

                $radios.each(function(groupe){
                    data.push($(this).find('input[type="radio"]:checked').length > 0);
                });

                if(data.length == $radios.length){
                    this.isValide = true;
                }

                this.$emit('validated',this.isValide);
            }
        }
    }
</script>
<style scoped>
    .option-title{
        margin:15px 0;
        font-size: 18px;
        color:#1e4b78;
        font-weight:bold;
    }
</style>