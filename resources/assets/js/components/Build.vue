<template>
    <div>

        <div v-if="type">
            <build-newsletter :title="title" v-if="type && isNormal" :type="type" site="2" :campagne="campagne" _token="_token" url="url"></build-newsletter>
            <build-newsletter-models :title="title" v-if="type && isModel" :type="type" site="2" :campagne="campagne" _token="_token" url="url"></build-newsletter-models>
        </div>

        <div class="row">
            <div class="col-md-7">

                <div class="component-menu">
                    <h5>Composants</h5>
                    <div class="component-bloc">
                        <a v-for="bloc in blocs" class="blocEdit" @click="selectBloc(bloc)">
                            <img :src="'newsletter/blocs/' + bloc.image" :alt="bloc.titre" /><span>{{ bloc.titre }}</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>
<style>
    body{
        background-color:#ff0000;
    }
</style>
<script>
    import BuildNewsletterModels from './BuildNewsletterModels.vue'
    import BuildNewsletter from './BuildNewsletter.vue'
    export default{
        props: ['campagne','_token','url','blocs'],
        data(){
            return{
                type:null,
                title:''
            }
        },
        computed: {
            isNormal: function () {
                return (this.type == 1) ||(this.type == 2) || (this.type == 3) || (this.type == 4) || (this.type == 6) || (this.type == 10) ? true : false;
            },

            isModel: function () {
                return (this.type == 5) || (this.type == 7) || (this.type == 8) || (this.type == 9) ? true : false;
            }
        },
        components:{
            'build-newsletter' : BuildNewsletter,
            'build-newsletter-models' : BuildNewsletterModels,
        },
        methods: {
            selectBloc : function(bloc){
                this.type = bloc.id;
                this.title = bloc.titre
            }
        }
    }
</script>
