<template>
    <div class="mt-15">

        <div class="form-group">
            <h4>Blocs de contenu</h4>
            <div id="listBlocs">

            </div><hr/>
            <h4>Ajouter un bloc de contenu</h4>
            <div>
                <a @click="showForm('text')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc texte</a>
                <a @click="showForm('loi')" class="new-bloc-content btn btn-success btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc lois</a>
                <a @click="showForm('autorite')" class="new-bloc-content btn btn-magenta btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc autorité</a>
                <a @click="showForm('lien')" class="new-bloc-content btn btn-orange btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc lien</a>
                <a @click="showForm('faq')" class="new-bloc-content btn btn-green btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc FAQ</a>

                <text-form v-if="form == 'text'" :page="page"></text-form>
                <loi-form v-if="form == 'loi'" :page="page"></loi-form>
                <lien-form v-if="form == 'lien'" :page="page"></lien-form>

            </div>
        </div>

    </div>
</template>
<style>
    .mt-15{
       margin-bottom:15px;
    }
</style>
<script>
    import TextForm from './partials/TextForm.vue';
    import LoiForm from './partials/LoiForm.vue';
    import LienForm from './partials/LienForm.vue';

    export default{
        props: ['page'],
        data(){
            return{
                form: null
            }
        },
        beforeMount: function () {
            this.page = this.page
        },
        methods: {
            showForm : function(type){
                this.form = type;
            }
        },
        components:{
            TextForm,LoiForm,LienForm
        }
    }
</script>
