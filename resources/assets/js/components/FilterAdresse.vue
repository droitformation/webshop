<template>
    <div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Rechercher</label>
                    <select name="type" class="form-control" v-model="selected" v-on:change="displayOptions">
                        <option v-for="type in types" v-bind:value="type.value">{{ type.name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Inclure</label>
                    <select multiple name="options[]" class="form-control max">
                        <option v-show="selected == 'user'" value="inscriptions">Inscriptions</option>
                        <option value="abos">Abos</option>
                        <option value="orders">Commandes</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</template>
<style>
    body{
        background-color:#ff0000;
    }
    .max{
        max-height:80px;
    }
</style>
<script>

    export default{
        data(){
            return{
                types: [
                   {'name' : 'Compte utilisateur', 'value' : 'user'},
                   {'name' : 'Adresse', 'value' : 'adresse'},
                ],
                selected: 'user'
            }
        },
        components:{
        },
        methods: {
            displayOptions : function(){

            }
        }
    }
</script>
