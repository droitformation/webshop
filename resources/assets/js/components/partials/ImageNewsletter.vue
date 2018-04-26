<template>
    <div>
        <img v-if="image" :src="image" class="img-responsive">
        <div class="upload-btn-wrapper" v-if="!image">
            <button class="btn btn-info btn-xs">Sélectionner image</button>
            <input type="file" v-on:change="onFileChange" class="form-control">
        </div>
        <button v-if="image" class="btn btn-success btn-xs" @click="remove">Retirer</button>
    </div>
</template>
<style>

</style>
<script>

    export default{
        data(){
            return{
                image:null,
                uploadImage:null
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                    vm.upload()
                };
                reader.readAsDataURL(file);
            },
            remove(){
                this.image = null;
            },
            upload(){
                axios.post('/admin/uploadNewsletter',{ image: this.image }).then(response => {
                    this.uploadImage = response.data.name;
                    this.$emit('imageUploaded', this.uploadImage)
                });
            }
        }
    }
</script>
