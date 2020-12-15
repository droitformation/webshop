<template>

  <div class="wrapper relative">

    <slot name="update"></slot>

    <build-avis-list :updated="updated" minus="170" :avis="avis" @update-list="updateList" :current="current"></build-avis-list>

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
       updated:false,
       path:  location.protocol + "//" + location.host+"/",
       choosen: this.current,
    }
  },
  methods: {
    updateList(choosen) {
      this.choosen = choosen;
      console.log('save');
      this.save();
    },
    save: function() {
      let self = this;

      axios.post(this.path + "admin/sondage/updateBuild",{
        id: this.sondage.id,
        avis: this.choosen
      }).then(function (response) {
        self.updated = true;
        setTimeout(() => {
          self.updated = false;
        }, 2000);
        console.log(response.data);
      }).catch(function (error) { console.log(error);});

    },
  }
}
</script>

<style scoped>

</style>