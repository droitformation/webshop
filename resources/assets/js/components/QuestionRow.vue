<template>
    <div>
        <div :class="'row row-avis mb-20 row-question ' + (question.class ? 'row-hidden' : '')" v-show="!question.hidden" v-for="question in rows">
            <div class="col-md-1"><a class="btn btn-sky btn-sm" :href="path + question.id"><i class="fa fa-edit"></i></a></div>
            <div class="col-md-6"><strong v-html="question.question_simple"></strong></div>
            <div class="col-md-3">{{ question.type_name }}</div>
            <div class="col-md-2 text-right">
              <button class="btn btn-orange btn-sm" @click="archive(question)">Cacher</button>
              <button class="btn btn-danger btn-sm ml-2" @click="remove(question)"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>
</template>
<style>

.row-question{
  padding: 2px 0;
}

.row-hidden{
  background: #fef3c7;
  transition: all .6s ease;
}

.row-avis{
  display: flex;
  align-items: center;
}

</style>
<script>
    export default {
        props: ['avis'],
        data () {
          return {
            rows : this.avis,
            updated : false,
            path: location.protocol + "//" + location.host+"/admin/avis/",
          }
        },
        mounted() {
            console.log('Component mounted.');
        },
        methods: {
          archive :function(question){
            var self = this;

            axios.post(this.path + 'updateAjax',{id : question.id }).then(function (response) {

              if(response.data){
                self.updated = true;
                //self.rows = response.data;
                question.class = 1;
                setTimeout(() => {
                  self.updated = false;
                  question.hidden = 1;
                }, 1000);
              }
            }).catch(function (error) { console.log(error);});
          },
          remove :function(question){
              var self = this;
              axios.post(this.path + 'deleteAjax',{id : question.id }).then(function (response) {
                  self.rows = response.data;
              }).catch(function (error) { console.log(error);});
          }
        }
    }
</script>
