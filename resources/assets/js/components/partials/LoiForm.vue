<template>
    <form :action="url" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" :value="Laravel.csrfToken">
        <h4>Bloc loi</h4>

        <div class="form-group">
            <label class="control-label">Contenu</label>
            <textarea v-model="content" name="content" class="form-control redactorSmall"></textarea>
        </div>

        <input name="type" value="loi" type="hidden">
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
                url: location.protocol + "//" + location.host+"/admin/content"
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
