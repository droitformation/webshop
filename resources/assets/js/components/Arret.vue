<template>
    <div>
        <article v-for="arret in list">
            <h3>{{ arret.reference }}</h3>
        </article>
    </div>
</template>
<style>

</style>
<script>
    export default{
        props: ['arrets'],
        mounted: function ()  {
            this.getArrets();
            console.log(this.arrets);
        },
        methods: {
            getArrets : function(){
               this.list = this.arrets;
            },
        },
        data(){
            return{
                list:[]
            }
        }
    }
</script>
