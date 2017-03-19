<template>
    <div>

        <div class="form-group">
            <input class="form-control" placeholder="Chercher une adresse..." type="text">
            <input value="2896" name="user_id" type="hidden">
            <input value="user_id" name="type" type="hidden">
        </div>

    </div>
</template>
<style>
    body{
        background-color:#ff0000;
    }
</style>
<script>

    export default{
        data(){
            return{
                msg:'hello vue'
            }
        },
        components:{
        }
    }
</script>
