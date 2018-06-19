<template>
    <div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Rechercher</label>
                    <select name="type" class="form-control" v-model="selected"  v-on:change="updateType">
                        <option v-for="type in types" v-bind:value="type.value">{{ type.name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Grouper par</label>
                    <div class="checkbox">
                        <label><input v-model="checked" name="group" type="radio" value="id"> &nbsp;Ne pas grouper</label>
                    </div>
                    <div class="checkbox">
                        <label><input v-model="checked" name="group" type="radio" value="email"> &nbsp;Même email</label>
                    </div>
                    <div class="checkbox">
                        <label><input v-model="checked" name="group" type="radio" value="last_name"> &nbsp;Même Nom</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

                <p><button type="button" class="btn btn-xs btn-success" @click="addTerm"><i class="fa fa-plus"></i> &nbsp;terme de recherche</button></p>
                <div v-for="(term,index) in searchTerms" class="row">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-xs btn-danger" @click="removeTerm(index)"><i class="fa fa-minus"></i></button>
                    </div>
                    <div class="col-md-4">
                        <select v-model="term.column" :name="'columns[' + index + ']'" class="form-control">
                            <option v-for="column in choosencolumns" v-bind:value="column.label">{{ column.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" v-model="term.value" class="form-control" :name="'terms[' + index + ']'" placeholder="Recherche...">
                    </div>
                </div>

                <div v-if="searchTerms.length" class="form-group form-group-border">
                    <div class="checkbox">
                        <label><input v-model="operator" name="operator" type="radio" value="and"> &nbsp;
                            Et <span class="text-muted">(tous les termes doivent correspondre)</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label><input v-model="operator" name="operator" type="radio" value="or"> &nbsp;
                            Ou <span class="text-muted">(chaque terme séparément)</span>
                        </label>
                    </div>
                </div>

            </div>
            <div class="col-md-2">
                <label class="control-label">&nbsp;</label><br/>
                <button class="btn btn-info" type="submit">Recherche</button>
            </div>
            <div class="col-md-2">
                <p>
                    <strong><span class="text-danger">La recherche se fait sur les champs existant pour chaque type:<br/></span></strong>
                    <strong>Comptes utilisateurs: </strong> Nom, prénom, email, entreprise<br/>
                    <strong>Adresses: </strong> Nom, prénom, email, entreprise, adresse, NPA, ville
                </p>
            </div>
        </div>

    </div>
</template>
<style>
    .max{
        max-height:80px;
    }
    .form-group-border{
        margin-top:20px;
    }
    .form-group-border .checkbox{
        padding-left:0;
    }
</style>
<script>

    export default{
        props: ['selected','checked','operator','terms'],
        data(){
            return{
                types: [
                   {'name' : 'Compte utilisateur', 'value' : 'user'},
                   {'name' : 'Adresse', 'value' : 'adresse'},
                ],
                searchTerms: this.terms,
                columns:
                {
                    user:[
                        {'name' : 'Nom', 'label' : 'last_name'},
                        {'name' : 'Prénom', 'label' : 'first_name'},
                        {'name' : 'Email', 'label' : 'email'},
                        {'name' : 'Entreprise', 'label' : 'company'},
                    ],
                    adresse:[
                        {'name' : 'Nom', 'label' : 'last_name'},
                        {'name' : 'Prénom', 'label' : 'first_name'},
                        {'name' : 'Email', 'label' : 'email'},
                        {'name' : 'Entreprise', 'label' : 'company'},
                        {'name' : 'Adresse ', 'label' : 'adresse '},
                        {'name' : 'NPA', 'label' : 'npa'},
                        {'name' : 'Ville', 'label' : 'ville'},
                    ]
                },
            }
        },
        computed: {
             choosencolumns: function () {
                return this.columns[this.selected];
             },
        },
        components:{
        },
        mounted: function () {

        },
        methods: {
            addTerm : function(){
                 this.searchTerms.push({ 'column' : 'first_name', 'value' : ' ' });
            },
            removeTerm: function(term){
                this.searchTerms.splice(term, 1)
            },
            updateType: function(){

            }
        }
    }
</script>
