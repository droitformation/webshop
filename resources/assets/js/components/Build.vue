<template>
    <div>

        <div v-if="type">
            <create-bloc
                    mode="create"
                    :site="site"
                    :title="title"
                    :type="type"
                    :campagne="campagne"
                    :newsletter="newsletter"
                    :_token="_token"
                    @cancel="cancel"
                    :url="url"></create-bloc>
        </div>

        <div class="row">
            <div class="col-md-7">

                <div id="componant" class="component-menu">
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

</style>
<script>
    import CreateBloc from './CreateBloc.vue'
    export default{
        props: ['campagne','_token','url','blocs','site','newsletter'],
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
            'create-bloc' : CreateBloc,
        },
        methods: {
            selectBloc : function(bloc){
                this.type  = bloc.id;
                this.title = bloc.titre
            },
            cancel: function(){
                this.type = null;
                this.title = ''
            },
        }
    }
</script>
