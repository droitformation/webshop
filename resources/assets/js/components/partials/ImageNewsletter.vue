<template>
    <div>

        <div class="upload-btn-wrapper" v-if="!image && visible">
            <image-uploader :wrapper="false" :id="id" name="file" @imageChoosen="imageChoosenSelect"></image-uploader>
        </div>

        <div class="upload-btn-wrapper" v-if="!image && visible">
            <button class="btn btn-info btn-xs">Télécharger image</button>
            <input type="file" v-on:change="onFileChange" class="form-control">
        </div>

        <div class="responsive-newsletter">
            <div v-if="image"><img :width="sizeImage" :src="image" class="img-responsive"></div>
            <div v-if="!image"><img :src="size" /></div>
        </div>

        <div class="btn-remove" v-if="visible">
            <button v-if="image" class="btn btn-success btn-xs" @click="remove">Changer l'image</button>
            <button v-if="isRemoved" class="btn btn-danger btn-xs" @click="cancel">Annuler</button>
        </div>

    </div>
</template>
<style>

    .btn-remove{
        margin-bottom: 10px;
        display: block;
        margin-top: 10px;
    }
    .responsive-newsletter{
        margin-bottom: 10px;
    }

</style>
<script>
    import ImageUploader from '../ImageUploader.vue';

    export default{
        props: ['model','type','visible','filename','id'],
        data(){
            return{
                isRemoved:false,
                image:null,
                uploadImage:null,
                big: 'http://www.placehold.it/560x200/EFEFEF/AAAAAA&text=image',
                small: 'http://www.placehold.it/130x140/EFEFEF/AAAAAA&text=image',
                hash:null,
            }
        },
        components:{
            'image-uploader' : ImageUploader,
        },
        mounted: function ()  {
            this.initialize();
            this.iframe();
        },
        computed: {
            size : function(){
                return this.type == 3 || this.type == 4 ? this.small : this.big;
            },
            sizeImage : function(){
                return this.type == 3 || this.type == 4 ? '130px' : '560px';
            }
        },
        methods: {
            imageChoosenSelect(filename){
                var lastURLSegment = filename.substr(filename.lastIndexOf('/') + 1);
                this.image = filename;
                this.uploadImage = lastURLSegment;
                this.$emit('imageUploaded', this.uploadImage)
            },
            makeHash(){
                this.hash = Math.random().toString(36).substring(7);
            },
            iframe(){
                this.$nextTick(function() {
                    var self = this;

                    $('#'+this.id).change(function() {
                        var image = $(this).val();
                        var lastURLSegment = image.substr(image.lastIndexOf('/') + 1);
                        self.image = image;
                        self.uploadImage = lastURLSegment;
                        self.$emit('imageUploaded', self.uploadImage)
                        console.log(lastURLSegment);
                    });
                });

            },
            initialize(){
                this.image = this.filename ? this.filename : null;
            },
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
                 this.isRemoved = true;
            },
            cancel(){
                 this.image = this.filename ? this.filename : null;
                 this.isRemoved = false;
                 this.iframe();
            },
            newSelected(){
                 this.iframe();
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
