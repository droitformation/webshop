<template>
    <div>
        <div class="text-right">
            <div class="btn-pull" style="margin-bottom:10px;">
                <a v-show="!add" @click="ajouter" class="btn btn-sm btn-success">Ajouter</a>
                <a v-show="add" @click="resetform" class="btn btn-sm btn-default">Fermer</a>
            </div>
        </div>
        <ul class="list-group">
            <li class="list-group-item" id="addOption" v-show="add">
                <div class="row">
                    <div class="col-md-4">
                        <select name="type" v-model="nouveau.type" class="form-control" v-on:change="selectType" >
                            <option value="checkbox">Case à cocher</option>
                            <option value="choix">Choix</option>
                            <option value="text">Texte</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control option-title" name="title" placeholder="Titre de l'option" v-model="nouveau.title">

                        <div class="list-group-option" v-if="nouveau.type == 'choix' &&  nouveau.groupe.length != 0">
                            <p v-for="groupe in nouveau.groupe">
                                <input class="form-control" :value="groupe.text" v-model="groupe.text" placeholder="Choix">
                            </p>
                            <a class="btn btn-xs btn-info" @click="addNewGroupe()"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <p class="text-right margTop"><a @click="ajouterOption" class="btn btn-sm btn-primary">Envoyer</a></p>
            </li>
        </ul>

        <ul class="list-group">
            <li v-for="option in list" :class="'list-group-item ' + option.type">
                <h4 class="list-option-title">{{ option.type_name }}</h4>
                <div class="row">
                    <div class="col-md-10">
                        <label><strong>Titre</strong></label>
                        <p v-if="!option.state">{{ option.title }}</p>
                        <div v-if="option.state">
                            <input class="form-control" name="title" type="text" v-model="option.title" v-bind:value="option.title">
                        </div>
                    </div>
                    <div class="col-md-2 text-right">
                        <div class="btn-group">
                            <a v-show="!option.state" @click="editOption(option)" class="btn btn-xs btn-info">éditer</a>
                            <a v-show="!option.state" @click="deleteOption(option)" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
                            <a v-show="option.state" @click="saveOption(option)" class="btn btn-xs btn-primary">sauvegarder</a>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="option.groupe.length != 0">
                    <div class="col-md-10">
                        <label><strong>Choix</strong></label>
                        <p v-if="!option.state" v-for="groupe in option.groupe">{{ groupe.text }}</p>

                        <div class="list-option-groupe" v-if="option.state">
                            <p v-for="groupe in option.groupe">
                                <input class="form-control" :value="groupe.text" v-model="groupe.text">
                            </p>
                            <a class="btn btn-xs btn-info" @click="addGroupe(option)"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

    </div>
</template>
<style>
    #addOption{
        margin-bottom:15px;
    }
    .margBottom{
      padding-bottom:5px;
    }
    .margTop{
        margin-top:10px;
    }
    .list-group-item {
        padding: 8px 15px 8px 15px;
    }
    .list-group-item label{
        margin-bottom: 5px;
        margin-top: 5px;
    }
    .list-option-groupe p{
           margin-bottom: 5px;
    }
    .list-group-option{
        margin-top:10px;
    }
    .list-group-option p {
        margin-bottom: 3px;
    }
    .option-title{
        border-color:#6f7271;
    }
</style>
<script>

export default {

    props: ['colloque','options'],
    data () {
        return {
            list: [],
            nouveau:{
                title: '',
                type: 'checkbox',
                groupe: [],
                colloque_id: this.colloque,
            },
            add : false,
            isText: false
        }
    },
    beforeMount: function () {
        this.getOptions();
    },
    methods: {
        getOptions : function(){
             this.list = _.orderBy(this.options, ['type'],['desc']);
             console.log(this.list);
        },
        selectType : function(){

            if(this.nouveau.type == 'choix')
            {
                this.nouveau.groupe.push({ text: ''});
            }
            else{
                this.nouveau.groupe = [];
            }
        },
        editOption : function(option){
            option.state = true;
        },
        ajouter : function(){
            this.add = true;
        },
        addNewGroupe: function(option) {
            this.nouveau.groupe.push({text: ''});
        },
        addGroupe: function(option) {
            option.groupe.push({ text: ''});
        },
        resetform :function(){
            this.add = false;
            this.nouveau = {
                title: '',
                option: '',
                type: 'checkbox',
                groupe: [],
                colloque_id: this.colloque,
            };
        },
        ajouterOption:function(){

            this.$http.post('/vue/option', { option : this.nouveau }).then((response) => {
                this.list = _.orderBy(response.body.options, ['type'],['desc']);
                this.resetform();
            }, (response) => {}).bind(this);

        },
        saveOption : function(option){

            this.$http.post('/vue/option/' + option.id, { option, '_method' : 'put' }).then((response) => {
               console.log(response.body.options);
               this.list = _.orderBy(response.body.options, ['type'],['desc']);
            }, (response) => {}).bind(this);

        },
        deleteOption :function(option){

            var shouldDelete = false;

            if(option.isUsed){
                var answer = confirm('Cette option est lié à des inscription, voulez-vous vraiment supprimer?');
                shouldDelete = answer ? true : false;
            }
            else{
                shouldDelete = true;
            }

            if(shouldDelete){
                   this.$http.post('/vue/option/' + option.id, { '_method' : 'DELETE' }).then((response) => {
                    this.list = _.orderBy(response.body.options, ['type'],['desc']);
                }, (response) => {}).bind(this);
            }
        }
    }
}
</script>