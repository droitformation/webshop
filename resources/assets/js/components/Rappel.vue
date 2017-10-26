
<template>
    <div>
        <div class="row">
            <div class="col-md-3">
               <form><button @click="generate" :id="getButtonId()" class="btn btn-brown btn-sm">Générer</button></form>
            </div>
            <div class="col-md-7">
                <ol>
                    <li class="rappel-item" v-for="rappel in list">
                        <a :href="rappel.doc_rappel" target="_blank" class="text-primary">
                            {{ rappel.date }} <small v-if="!rappel.doc_rappel" class="text-muted">pdf non crée</small>
                        </a>
                        <button @click="remove(rappel.id)" class="btn btn-danger btn-xs pull-right"><i class="fa fa-times"></i></button>
                        <div class="clearfix"></div>
                    </li>
                </ol>
                <i v-show="loading" class="fa fa-spinner fa-spin"></i>
            </div>
            <div class="col-md-2">
                <ol style="margin-left: 5px;padding-left: 3px;">
                    <li class="rappel-item" v-for="rappel in list">
                        <a :href="'admin/inscription/rappel/' +rappel.id" target="_blank" class="btn btn-default btn-xs">
                             + BV
                        </a>
                    </li>
                </ol>
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
            isVisible: true,
            toPrint:false,
        }
    },
    computed: {
        computedOrder: function () {
            return this.item
        },
        computedNumber: function () {
            return  'makeRappel_' + this.item
        }
    },
    mounted: function ()  {
        this.getRappels();
        this.loading = false;
    },
    methods: {
        getButtonId : function(){
            return  'makeRappel_' + this.item
        },
        getRappels : function(){
           this.list = this.rappels;
        },
        updateRappels : function(rappels){
           this.list = rappels;
        },
        generate: function(e) {
              this.loading   = true;
              this.isVisible = false;

              e.preventDefault();
              var print = this.toPrint ? 1 : null;
              this.$http.post('/admin/' + this.path + '/rappel/generate', { id: this.item , print: print}).then((response) => {

                  var self = this;
                  //small delay for pdf generation completion
                  setTimeout(function(){
                       self.updateRappels(response.body.rappels);
                       self.loading = false;
                  }, 500);

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