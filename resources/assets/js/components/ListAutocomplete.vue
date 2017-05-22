<template>
    <div>
        <input v-show="!hasChosen" :class="'form-control search-adresse-autocomplete_' + type" placeholder="Chercher une adresse..." type="text">
        <div v-if="hasChosen" class="choice-adresse autocomplete-bloc">
            <input :name="type" :value="chosen.user_id" type="hidden">

            <div class="panel panel-primary">
                <div class="panel-body panel-colloque">
                    <span class="no-adresse">{{ chosen.user_id }}</span>

                    <span v-if="chosen.company">{{ chosen.company }}</span>
                    <span>{{ chosen.civilite }}</span>
                    <span><a target="_blank" :href="'admin/user/' + chosen.user_id">{{ chosen.name }}</span>
                    <span v-if="chosen.cp">{{ chosen.cp }}</span>
                    <span>{{ chosen.adresse }}</span>
                    <span>{{ chosen.npa }} {{ chosen.ville }}</span>
                    <button type="button" class="btn btn-danger btn-xs pull-right" @click.prevent="remove">changer</button>
                </div>
            </div>

        </div>
    </div>
</template>
<style>
    .autocomplete-bloc{
        padding:10px 0;
        margin-top:5px;
    }
    .autocomplete-bloc span{
        display:block;
    }
    .autocomplete-bloc .btn.btn-danger{
           margin-top:8px;
    }
    .no-adresse {
        position: absolute;
        top: 5px;
        right: 5px;
        display: block;
        color: #7b7b7b;
        font-size: 12px;
        border: 1px solid #d4d4d4;
        padding: 3px;
        width:auto;
        text-align: center;
    }
</style>

<script>
    export default{
        props: ['type','chosen_id'],
        data(){
            return{
                chosen: {
                    civilite : '',
                    name : '',
                    company: '',
                    adresse: '',
                    cp: '',
                    npa: '',
                    ville: '',
                    user_id: null
                },
                hasChosen: false,
            }
        },
        mounted: function ()  {

             if(this.chosen_id){

               this.fetch();
             }

            this.$nextTick(function() {

                let self = this;

                $(".search-adresse-autocomplete_" + this.type).autocomplete({
                    source    : base_url + 'vue/autocomplete',
                    minLength : 3,
                    select    : function( event, ui ) {

                         self.chosen = ui.item.user;
                         self.hasChosen = true;
                         console.log(ui.item.user);
                         return false;
                    }
                }).autocomplete( "instance" )._renderItem = function( ul, item ){
                    return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span><span>" + item.company + "</span></a>").appendTo(ul);
                };

            });
        },
        methods: {
            remove () {
                this.hasChosen = false;
                this.chosen = {
                    civilite : '',
                    name : '',
                    company: '',
                    adresse: '',
                    cp: '',
                    npa: '',
                    ville: '',
                    user_id: null
                };
                this.user_id = null;
            },
            updateOptions(options){
                this.options = options;
            },
            fetch () {

                this.$http.get('admin/user/getUser/' + this.chosen_id, {}).then((response) => {

                    console.log(response.body);
                    this.chosen = response.body;
                    this.hasChosen = true;

                    // self.loading = false;
                }, (response) => { }).bind(this);
            },
        }
    }
</script>
