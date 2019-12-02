<template>
    <div class="form-group">
        <label><strong>Options</strong></label>
        <p><button type="button" class="btn btn-primary" @click="add"><i class="fa fa-plus"></i> ajouter une option</button></p>
        <div v-for="(item, index) in choices" class="choix-input">
            <input type="text" class="form-control"  :value="item" :name="'conference[choices]['+ index +']'">
            <button type="button" class="btn btn-danger" @click="remove"><i class="fa fa-minus"></i></button>
        </div>
    </div>
</template>
<style>
    .choix-input{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin: 5px 0;
    }
</style>
<script>
    export default {
        props: ['choix'],
        components: {},
        data () {
            return {
                choices: this.choix ? this.choix : []
            }
        },
        mounted() {
            console.log('Component mounted.');
        },
        methods: {
            add : function(){
                this.choices.push(['option']);
            },
            remove : function(){
                this.choices.splice(-1);
            },
        }
    }
</script>
