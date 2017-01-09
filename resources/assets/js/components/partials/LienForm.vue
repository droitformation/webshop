<template>
    <form class="form add-bloc-form">
        <h4>Bloc lien</h4>

        <div class="form-group">
            <label class="control-label">Titre</label>
            <input v-model="title" name="title" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label class="control-label">Contenu</label>
            <textarea v-model="content" name="content" class="form-control redactorSimple"></textarea>
        </div>

        <input name="type" value="lien" type="hidden">
        <input name="page_id" v-bind:value="page" type="hidden">
        <button type="button" class="btn btn-magenta btn-sm add-bloc-btn">Ajouter</button>

    </form>
</template>
<style></style>
<script>
    export default{
        props: ['page'],
        data(){
            return{

            }
        }
    }
</script>
