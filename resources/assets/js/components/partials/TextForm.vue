<template>
    <form>
        <h4>Bloc texte</h4>

        <div class="form-group">
            <label class="control-label">Titre</label>
            <input v-model="title" name="title" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label class="control-label">Style de bloc</label>
            <select class="form-control" v-model="style" name="style">
                <option value="">Choisir</option>
                <option value="agenda">Bloc agenda rouge</option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">Contenu</label>
            <textarea v-model="content" name="content" class="form-control redactorSmall"></textarea>
        </div>

        <input name="type" value="text" type="hidden">
        <input name="page_id" v-bind:value="page" type="hidden">
        <button type="button" class="btn btn-primary btn-sm">Ajouter</button>

    </form>
</template>
<style></style>
<script>
    export default{
        props: ['page'],
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
        }
    }
</script>
