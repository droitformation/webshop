<template>
    <div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Rechercher</label>
                    <select name="type" class="form-control" v-model="selected">
                        <option v-for="type in types" v-bind:value="type.value">{{ type.name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Grouper par</label>
                    <div class="checkbox">
                        <label><input v-model="checked" name="group" type="radio" value="user_id"> &nbsp;Ne pas grouper</label>
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
                        <select :name="'columns[' + index + ']'" class="form-control">
                            <option v-for="column in columns" v-bind:value="column.label">{{ column.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" v-model="term.text" class="form-control" v-bind:value="term.text" :name="'terms[' + index + ']'" placeholder="Recherche...">
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
                searchTerms:[],
                columns: [
                   {'name' : 'Nom', 'label' : 'last_name'},
                   {'name' : 'Prénom', 'label' : 'first_name'},
                   {'name' : 'Email', 'label' : 'email'},
                   {'name' : 'Entreprise', 'label' : 'company'},
                   {'name' : 'Adresse ', 'label' : 'adresse '},
                   {'name' : 'NPA', 'label' : 'npa'},
                   {'name' : 'Ville', 'label' : 'ville'},
                ],

            }
        },
        components:{
        },
        mounted: function () {
            //this.checked = this.checked;
        },
        methods: {
            addTerm : function(){
                 this.searchTerms.push({ 'text' : ' ' });
            },
            removeTerm: function(term){
                this.searchTerms.splice(term, 1)
            }
        }
    }
</script>
