<template>
    <div>

        <div v-if="list">
            <div v-for="content in list">
                <build-newsletter-models v-if="content.type_content == 'model'"
                                         :type="content.type_id"
                                         :site="site"
                                         :newsletter="newsletter"
                                         mode="edit"
                                         :content="content"
                                         :campagne="campagne"
                                         @deleteContent="deleteContentBloc"
                                         :_token="_token"
                                         :url="url">
                </build-newsletter-models>

                <build-newsletter-group v-if="content.type_content == 'group'"
                                         :type="content.type_id"
                                         :site="site"
                                         :newsletter="newsletter"
                                         mode="edit"
                                         :content="content"
                                         :campagne="campagne"
                                         @deleteContent="deleteContentBloc"
                                         :_token="_token"
                                         :url="url">
                </build-newsletter-group>

                <build-newsletter v-if="content.type_content == 'content'"
                                         :type="content.type_id"
                                         :site="site"
                                         mode="edit"
                                         :model="content"
                                         @deleteContent="deleteContentBloc"
                                         :campagne="campagne"
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
        props: ['campagne','_token','url','site','contents','newsletter'],
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

            }
        }
    }
</script>
