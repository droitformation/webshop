<template>
    <form class="bloc-content">
        <h4>Bloc {{ bloc.type }}</h4>

        <div v-if="bloc.type == 'loi' || bloc.type == 'text' || bloc.type == 'lien'" class="form-group">
            <label class="control-label">Titre</label>
            <input v-model="bloc.title" name="title" type="text" class="form-control">
        </div>

        <div v-if="bloc.type == 'text'" class="form-group">
            <label class="control-label">Style de bloc</label>
            <select class="form-control" v-model="bloc.style" name="style">
                <option value="">Choisir</option>
                <option value="agenda">Bloc agenda rouge</option>
            </select>
        </div>

        <div v-if="bloc.type == 'faq'" class="form-group">
            <label class="control-label">Catégorie</label>
            <select v-model="bloc.categorie_id" name="categorie_id" class="form-control">
                <option v-for="categorie in categories" v-bind:value="categorie.id">{{ categorie.title }}</option>
            </select>
        </div>

        <div v-if="bloc.type == 'autorite'" class="form-group">
            <label class="control-label">Image</label>
            <img class="thumbnail" :src="bloc.image" alt="image">
            <div class="list-group">
                <div class="list-group-item">
                    <input name="file" type="file">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">Contenu</label>
            <textarea v-model="bloc.content" name="content" class="form-control redactorSmall"></textarea>
        </div>

        <input name="type" v-bind:value="bloc.type" type="hidden">
        <input name="page_id" v-bind:value="bloc.page_id" type="hidden">
        <button type="button" class="btn btn-primary btn-sm">Éditer</button>

    </form>
</template>
<style>
    .thumbnail{
        margin-bottom:4px;
    }
</style>
<script>
    export default{
        props: ['bloc','categories'],
        data(){
            return{

            }
        },
        mounted: function () {
            this.makeData();
        },
        methods: {
            makeData : function(){

               this.$nextTick(function(){
                    $('.redactorSmall').redactor({
                        minHeight: 50,
                        maxHeight: 270,
                        lang: 'fr',
                        plugins: ['iconic'],
                        buttons  : ['format','bold','italic','link','|','unorderedlis','orderedlist']
                    });
               });
            }
        },
        watch: {
            bloc: function () {
                $('.redactorSmall').redactor('core.destroy');
                 this.makeData();
            }
        }
    }
</script>
