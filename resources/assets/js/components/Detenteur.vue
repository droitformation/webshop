<template>
    <div class="mt-15">
        <p><strong>{{ detenteur.civilite }}</strong></p>
        <p><a :href="'admin/user/' + detenteur.id">{{ detenteur.name }}</a></p>
        <p>{{ detenteur.email }}</p>
    </div>
</template>
<style>
    .mt-15{
       margin-bottom:15px;
    }
</style>
<script>
    export default{
        props: ['user'],
        data(){
            return{}
        },
        beforeMount: function () {
            this.getData();
        },
        methods: {
            getData : function(){
                 this.detenteur = this.user;
            }
        }
    }
</script>
