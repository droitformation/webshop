
<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <button @click="generate" class="btn btn-brown btn-sm">Générer un rappel</button>
            </div>
            <div class="col-md-8">
                <ol>
                    <li class="rappel-item" v-for="rappel in list">
                        <a :href="rappel.doc_rappel" class="text-primary">{{ rappel.date }}</a>
                            <button @click="remove(rappel.id)" class="btn btn-danger btn-xs pull-right"><i class="fa fa-times"></i></button>
                        <div class="clearfix"></div>
                    </li>
                </ol>
                <i v-show="loading" class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
</template>
<styles></styles>
<script>
export default {

    props: ['item','rappels','path'],
    data () {
        return {
            list: [],
            loading: true,
            isVisible: true
        }
    },
    computed: {
        computedOrder: function () {
            return this.item
        }
    },
    mounted: function ()  {
        this.getRappels();
        this.loading = false;
    },
    methods: {
        getRappels : function(){
           this.list = this.rappels;
        },
        updateRappels : function(rappels){
           this.list = rappels;
        },
        generate: function() {
              this.loading   = true;
              this.isVisible = false;
              // POST /someUrl
              this.$http.post('/admin/' + this.path + '/rappel/generate', { id: this.item }).then((response) => {

                  // set data on vm
                  this.updateRappels(response.body.rappels);
                  this.loading = false;

              }, (response) => {
                // error callback
              }).bind(this);
        },
        remove : function(id){
             this.loading = true;
             this.$http.post('/admin/' + this.path + '/rappel/' + id, { item : this.item, '_method' : 'DELETE' }).then((response) => {
                  console.log(response);

                  // set data on vm
                  this.updateRappels(response.body.rappels);
                  this.loading = false;

             }, (response) => {
             }).bind(this);

        }
    }
}
</script>