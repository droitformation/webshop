<template>
    <form method="post" :action="base_url">
        <h4>Bloc texte</h4>

        <div class="form-group">
            <label class="control-label">Titre</label>
            <input v-model="bloc.title" name="title" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label class="control-label">Style de bloc</label>
            <select class="form-control" v-model="style" name="bloc.style">
                <option value="">Choisir</option>
                <option value="agenda">Bloc agenda rouge</option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">Contenu</label>
            <textarea v-model="content" name="bloc.content" class="form-control redactorSmall"></textarea>
        </div>

        <button type="button" @click="addContent" class="btn btn-primary btn-sm">Ajouter</button>
    </form>
</template>
<style></style>
<script>
    export default{
        props: ['page'],
        data(){
            return{
                bloc: {
                    title: '',
                    content: '',
                    style: '',
                    type: 'text',
                    page_id: this.page
                }
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
            },
            ajouter:function(){

            this.$http.post('/admin/content', { bloc : this.bloc }).then((response) => {

                 this.$emit('option-select', this.response.body.id)

            }, (response) => {
            // error callback
            }).bind(this);
        },

        }
    }
</script>
