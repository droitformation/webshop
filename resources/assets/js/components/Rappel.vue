
<template>
    <div>
        <div class="row">
            <div class="col-md-2">
               <form><button @click="generate" :id="getButtonId()" class="btn btn-brown btn-sm">Générer un rappel</button></form>
            </div>
            <div class="col-md-3">
                <input class="form-control" v-model="montant" placeholder="Montant déjà payé">
            </div>
            <div class="col-md-3">
                <input class="form-control datepicker" v-model="payed_at" placeholder="Payé le">
            </div>
            <div class="col-md-4">
                <ol>
                    <li class="rappel-item" v-for="rappel in list">
                        <a :href="rappel.doc_rappel" target="_blank" class="text-primary">{{ rappel.date }}</a>
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
            montant: null,
            payed_at: null,
            loading: true,
            isVisible: true
        }
    },
    mounted: function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            language: 'fr'
        });
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
              // POST
              this.$http.post('/admin/' + this.path + '/rappel/generate', { id: this.item, montant: this.montant }).then((response) => {

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