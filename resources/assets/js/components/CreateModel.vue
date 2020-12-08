<template>

  <div class="row relative">
    <div class="col-md-4">
      <div class="model-avis">
         <button class="options-item" v-for="avi in filteredList">
            <label class="d-block type_name"><small>{{ avi.type_name }}</small></label>
            <div v-html="avi.question"></div>
         </button>
      </div>
    </div>
    <div class="col-md-8">

      <div class="card card-full">
        <div class="card-body">
           <input type="text" v-model="search" placeholder="Recherche" class="form-control"/>
        </div>
      </div>

      {{ questions }}

    </div>
  </div>

</template>

<script>
  export default {
    props: ['avis'],
    mounted() {
      console.log('Component mounted.');
    },
    data() {
      return {
         choosen: [],
         questions: this.avis,
         search: '',
      }
    },
    computed: {
      filteredList() {
        return Object.values(this.questions).filter(question => {
            console.log(question);
            return question.question_simple.toLowerCase().includes(this.search.toLowerCase())
        });
      }
    },
    methods: {
       // getInfo(){},
    }
  }
</script>
