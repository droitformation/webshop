
<template>
    <div>
        <a :href="link" v-if="link" target="_blank" class="btn btn-default btn-sm">Facture en pdf</a>
        <i v-show="loading" class="fa fa-spinner fa-spin"></i>
        <button v-show="isVisible" v-if="!link"  @click="generate" class="btn btn-inverse btn-sm">Générer</button>
    </div>
</template>
<styles></styles>
<script>
export default {

    props: ['order','path'],
    data () {
        return {
           link: null,
           loading: false,
           isVisible: true
        }
    },
    computed: {
        computedOrder: function () {
            return this.order
        },
        computedPath: function () {
            return this.path
        }
    },
    methods: {
        generate: function() {
            this.loading = true;
            this.isVisible = false;

            var self = this;
            axios.post('/admin/' + this.path + '/generate', { id: this.order }).then(function (response) {
                console.log(response.data);
                // set data on vm
                self.link    = response.data.link;
                self.loading = false;
                self.isVisible = true;
            }).catch(function (error) { console.log(error);});
        }
    }
}
</script>