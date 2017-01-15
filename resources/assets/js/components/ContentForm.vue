<template>
    <div class="mt-15">

        <div class="form-group">
            <h4>Blocs de contenu</h4>

            <div v-for="(bloc,index) in contents">
                <h5><i :class="'fa fa-' +  icons[index]"></i> &nbsp;{{ index }}</h5>
                <ul class="list-group sortcontent">
                    <li v-for="content in bloc" class="list-group-item">
                        <div class="row">
                            <div class="col-md-9">
                                {{ content.title }}
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="btn btn-danger btn-xs pull-right"><i class="fa fa-times"></i></a>
                                <a @click="editContent(content)" class="btn btn-info btn-xs pull-right">éditer</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <hr/>
            <h4>Ajouter un bloc de contenu</h4>
            <div>
                <a @click="showForm('text')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc texte</a>
                <a @click="showForm('loi')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc lois</a>
                <a @click="showForm('autorite')" class="btn btn-magenta btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc autorité</a>
                <a @click="showForm('lien')" class="btn btn-orange btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc lien</a>
                <a @click="showForm('faq')" class="btn btn-green btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc FAQ</a>

                <text-form v-if="form == 'text'" :page="page"></text-form>
                <loi-form v-if="form == 'loi'" :page="page"></loi-form>
                <lien-form v-if="form == 'lien'" :page="page"></lien-form>
                <faq-form v-if="form == 'faq'" :page="page" :categories="categories"></faq-form>
                <autorite-form v-if="form == 'autorite'" :page="page"></autorite-form>


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
    import FaqForm from './partials/FaqForm.vue';
    import AutoriteForm from './partials/AutoriteForm.vue';

    //import ContentEdit from './partials/ContentEdit.vue';

    export default{
        props: ['page','categories','contents'],
        data(){
            return{
                form: null,
                edit:false,
                content: null,
                blocs:[],
                icons : {
                    Text : 'align-justify',
                    Autorite : 'building',
                    Loi  : 'gavel',
                    Lien :'link',
                    Faq  : 'question-circle',
                }
            }
        },
        beforeMount: function () {
            this.page = this.page;
            this.categories = this.categories;
            this.contents = this.contents;
        },
        methods: {
            showForm : function(type){
                this.form = type;
                this.edit = false;
            },
            editContent: function(content){

                this.content = content;
                this.edit = true;
                this.form = null;
            }
        },
        components:{
            TextForm,LoiForm,LienForm,FaqForm,AutoriteForm
        }
    }
</script>
