<template>
    <div>

        <div v-if="list">
            <div v-for="content in list">
                <build-newsletter-models v-if="content.type_content == 'model'"
                                         :type="content.type_id"
                                         :site="site"
                                         mode="edit"
                                         :content="content"
                                         :campagne="campagne" _token="_token"
                                         @deleteContent="deleteContentBloc"
                                         :_token="_token"
                                         :url="url">
                </build-newsletter-models>

                <build-newsletter v-if="content.type_content == 'content'"
                                         :type="content.type_id"
                                         :site="site"
                                         mode="edit"
                                         :model="content"
                                         @deleteContent="deleteContentBloc"
                                         :campagne="campagne" _token="_token"
                                         :_token="_token"
                                         :url="url">
                </build-newsletter>
            </div>

        </div>

    </div>
</template>
<style>
</style>
<script>
    import BuildNewsletterModels from './BuildNewsletterModels.vue'
    import BuildNewsletter from './BuildNewsletter.vue'

    export default{
        props: ['campagne','_token','url','site','contents'],
        data(){
            return{
                list :[]
            }
        },
        computed: {},
        components:{
            'build-newsletter' : BuildNewsletter,
            'build-newsletter-models' : BuildNewsletterModels,
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            initialize(){
                this.list = this.contents;
            },
            deleteContentBloc(content){
                var self = this;
                axios.post(self.url + '/' + content.id , { '_method' : 'DELETE', 'campagne_id' : self.campagne.id, 'id' : content.id }).then(function (response) {
                    self.list = response.data;
                }).catch(function (error) { console.log(error);});
            }
        }
    }
</script>
