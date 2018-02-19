<template>
    <div>

        <div class="input-group" v-show="!hasChosen">
            <span class="input-group-addon">
                <img v-show="searching" height="15px" src="images/default.svg">
            </span>
            <input :class="'search-input form-control search-adresse-autocomplete_' + type" placeholder="Chercher..." type="text">
            <span class="input-group-addon" style="border:none;background:#fff; width:100px;">
                <span class="empty-text text-danger pull-right" v-show="noResult">Aucun résultat</span>
            </span>
        </div>

        <div v-if="hasChosen" class="choice-adresse autocomplete-bloc">
            <input :name="type" :value="chosen.user_id" type="hidden">

            <div class="panel panel-primary">
                <div class="panel-body panel-colloque">
                    <span class="no-adresse">{{ chosen.user_id }}</span>

                    <span v-if="chosen.company && (chosen.company != chosen.company)"><strong>{{ chosen.company }}</strong></span>
                    <span v-if="chosen.civilite">{{ chosen.civilite }}</span>
                    <span><a target="_blank" :href="'admin/user/' + chosen.user_id">{{ chosen.name }}</a></span>
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
    .empty-text{
        margin-top:5px;
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
                noResult: false,
                searching: false
            }
        },
        mounted: function ()  {

             if(this.chosen_id){

               this.fetch();
             }

            this.$nextTick(function() {

                let self = this;

                $(".search-adresse-autocomplete_" + this.type).keypress(function(e) {
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if(code == 13) { //Enter keycode
                        return false;
                    }
                });

                $(".search-adresse-autocomplete_" + this.type).blur(function(){
                     if($(this).val() === '') {
                          self.noResult = false;
                     }
                 })

                $(".search-adresse-autocomplete_" + this.type).autocomplete({
                    source    : base_url + 'vue/autocomplete',
                    minLength : 2,
                    search: function( event, ui ) {
                        self.searching = true;
                    },
                    select : function( event, ui ) {
                         self.chosen = ui.item.user;
                         self.hasChosen = true;
                         self.searching = false;
                         self.noResult = false;
                         console.log(ui.item.user);
                         return false;
                    },
                    response: function(event, ui) {
                        if (ui.content.length === 0) {
                            self.searching = false;
                            self.noResult = true;
                        }
                    },
                    change: function(event, ui) {
                        console.log($(this).val());
                         self.searching = false;
                        if($(this).val() === '') {
                             self.noResult = false;
                        }
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

                var self = this;
                axios.post('admin/user/getUser/' + this.chosen_id, {}).then(function (response) {
                    console.log(response.data);
                    self.chosen = response.data;
                    self.hasChosen = true;
                    self.noResult = false;
                }).catch(function (error) { console.log(error);});
            },
        }
    }
</script>
