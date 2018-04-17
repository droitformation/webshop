<template>
    <div>
        <div class="row">
            <div class="col-md-7" id="StyleNewsletterCreate">
                <!-- Bloc content-->
                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">

                    <td valign="top" width="375" class="resetMarge contentForm">
                                {{ model }}
                    </td>

                    <!-- Bloc image droite-->
                    <td width="25" class="resetMarge"></td><!-- space -->
                    <td valign="top" align="center" width="160" class="resetMarge">

                    </td>

                </table>
                <!-- Bloc content-->
            </div>
            <div class="col-md-5">
                <form name="blocForm" class="form-horizontal" method="post" :action="url"><input name="_token" :value="_token" type="hidden">
                    <div class="panel panel-success">
                        <div class="panel-body">

                            <div class="form-group">
                                <select class="form-control form-required required" v-model="model" name="id" v-on:change="updateModel">
                                    <option v-for="model in models" v-bind:value="model">{{ model.title }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="btn-group">
                                    <input type="hidden" :value="type" name="type_id">
                                    <input type="hidden" :value="campagne.id" name="campagne">
                                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                                    <button type="button" class="btn btn-sm btn-default cancelCreate">Annuler</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style>

</style>
<script>

    export default{

        props: ['type','campagne','_token','url','site'],

        data(){
            return{
                model:null,
                choosen: [],
                models: [],
            }
        },
        computed: {

        },
        mounted: function ()  {
            this.getModels('admin/ajax/product');
        },
        methods: {
            getModels: function(route) {
                var self = this;
                axios.get(route).then(function (response) {
                      self.models = response.data;
                }).catch(function (error) { console.log(error);});
            },
            updateModel(model){
            },
        }
    }
</script>
