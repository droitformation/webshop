<template>
    <div>

        <!-- Bloc content-->
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">

            <tr>
                <!-- Bloc image gauche-->
                <td v-if="type == 4" valign="top" align="center" width="160" class="resetMarge">
                    <image-newsletter :visible="1" :type="type" @imageUploaded="imageUploadedUpdate"></image-newsletter>
                </td>
                <td v-if="type == 4" width="25" class="resetMarge"></td><!-- space -->

                <td valign="top" :width="widthTable" class="resetMarge contentForm">
                    <image-newsletter :visible="1" :type="type" v-if="(type == 1 || type == 2)" @imageUploaded="imageUploadedUpdate"></image-newsletter>
                    <h3 :style="align" v-html="newbloc.titre"></h3>
                    <div v-html="newbloc.contenu"></div>
                </td>

                <!-- Bloc image droite-->
                <td v-if="type == 3 || type == 10" width="25" class="resetMarge"></td><!-- space -->
                <td v-if="type == 3 || type == 10" valign="top" align="center" width="160" class="resetMarge">
                    <image-newsletter :visible="1" :type="type" v-if="type == 3" @imageUploaded="imageUploadedUpdate"></image-newsletter>
                    <img v-if="categorie" :src="imgcategorie" class="img-responsive">
                </td>
            </tr>

        </table>
        <!-- Bloc content-->

    </div>
</template>
<style>

</style>
<script>
  import ImageNewsletter from '../partials/ImageNewsletter.vue';

    export default{
        props: ['newbloc','categorie','type'],
        components:{ 'image-newsletter' : ImageNewsletter },
        computed: {
            widthTable: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 6) ? '560' : '375';
            },
            hasText: function () {
                return (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            hasTitle: function () {
                return (this.type == 1) || (this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },
            align: function () {
                return (this.type == 1) ? 'text-align:center;' : 'text-align:left;';
            },
            imgcategorie : function(){
                return this.categorie ? this.categorie.path : '';
            },
        },
        data(){
            return{
                isImage:null,
            }
        },
        methods: {
            imageUploadedUpdate(value){
                this.$emit('imageUploaded', value)
            },
        }
    }
</script>
