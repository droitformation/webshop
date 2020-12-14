<template>

  <div class="wrapper relative">

    <build-avis-list :avis="avis" @update-list="updateList" :current="current"></build-avis-list>

  </div>

</template>

<script>

import BuildAvisList from './BuildAvisList.vue'

export default {
  props: ['avis','sondage','current'],
  components: {
    BuildAvisList
  },
  data() {
    return {
      path:  location.protocol + "//" + location.host+"/",
      choosen: this.current,
    }
  },
  methods: {
    updateList(choosen) {
      this.choosen = choosen;
      this.save();
    },
    save: function() {
      let self = this;

      axios.put(this.path + "admin/sondage/" + this.sondage.id,{
        id: this.sondage.id,
        avis: this.choosen
      }).then(function (response) {
        console.log(response.data);
      }).catch(function (error) { console.log(error);});

    },
  }
}
</script>

<style scoped>

</style>