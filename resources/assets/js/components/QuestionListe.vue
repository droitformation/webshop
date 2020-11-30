<template>
  <div>
      <table class="table">
        <thead>
            <tr>
                <th class="col-sm-1">Action</th>
                <th class="col-sm-6">Question</th>
                <th class="col-sm-3">Type</th>
                <th class="col-sm-2 no-sort"></th>
            </tr>
        </thead>
        <tbody class="selects">
            <tr v-for="question in rows">
                <td><a class="btn btn-sky btn-sm" href="#"><i class="fa fa-edit"></i></a></td>
                <td><strong v-html="question.question">{{ question.question }}</strong></td>
                <td>{{ question.type_name }}</td>
                <td class="text-right">
                  <button class="btn btn-danger btn-sm" @click="remove(question)"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </tbody>
      </table>
  </div>
</template>

<script>
    export default {
        props: ['avis'],
        data () {
          return {
            rows : this.avis,
          }
        },
        mounted() {
            console.log('Component mounted.');
            // path
        },
        methods: {
          remove :function(question){

            var self = this;

            axios.post(question.path,{id : question.id }).then(function (response) {
              self.rows = response.data;
            }).catch(function (error) { console.log(error);});

          }
        }
    }
</script>
