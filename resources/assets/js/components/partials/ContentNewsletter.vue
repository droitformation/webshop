<template>
    <div>
        <h2 v-html="create.titre"></h2>
        <div v-html="create.contenu"></div>
    </div>
</template>
<style>
    body{
        background-color:#ff0000;
    }

</style>
<script>

    export default{
        props: ['titre','contenu'],
        data(){
            return{
                titre : '',
                contenu : ''
            }
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            initialize : function(){

                this.$nextTick(function(){
                    var self = this;
                    $('.redactorBuild').redactor({
                        minHeight: 50,
                        maxHeight: 270,
                        lang: 'fr',
                        plugins: ['imagemanager','filemanager'],
                        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                        fileManagerJson: 'admin/fileJson?_token=' +   $('meta[name="_token"]').attr('content'),
                        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
                        plugins: ['iconic'],
                        buttons  : ['html','formatting','bold','italic','link','image','file','|','unorderedlist','orderedlist'],
                        blurCallback:function(e){
                            var text = this.code.get();
                            self.create.contenu = this.code.get();
                        }
                    });

                });
            },
            updateValue: function (value) {
              this.$emit('input', titre);
            }
        }
    }
</script>
