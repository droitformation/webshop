<template>

  <div class="wrapper relative">

    <build-avis-list :updated="updated" :avis="avis" @update-list="updateList" :current="current">
      <template v-slot:update>
        <div class="card card-full">
          <div class="card-body">
            <h4 class="modele-title">Nom du modèle</h4>

            <dl class="dl-horizontal dl-simple">
              <dt class="mb-1">Titre</dt>
              <dd class="mb-1"><input name="title" v-model="original.title" @blur="save" class="form-control inline"></dd>
              <dt>Description</dt>
              <dd><input name="description" v-model="original.description" @blur="save" class="form-control inline"></dd>
            </dl>

          </div>
        </div>
      </template>
    </build-avis-list>

  </div>

</template>

<script>

import BuildAvisList from './BuildAvisList.vue'

export default {
  props: ['avis','modele','current'],
  components: {
    BuildAvisList
  },
  data() {
    return {
      updated:false,
      path:  location.protocol + "//" + location.host+"/",
      choosen: this.current,
      original: {
        title: this.modele.title,
        description: this.modele.description,
      }
    }
  },
  methods: {
    updateList(choosen) {
      this.choosen = choosen;
      this.save();
    },
    save: function() {
      let self = this;

      axios.put(this.path + "admin/modele/" + this.modele.id,{
          id: this.modele.id,
          title: this.original.title,
          description: this.original.description,
          avis: this.choosen
      }).then(function (response) {
        self.updated = true;
        setTimeout(() => {
          self.updated = false;
        }, 2000);
      }).catch(function (error) { console.log(error);});

    },
  }
}
</script>

<style scoped>

</style>