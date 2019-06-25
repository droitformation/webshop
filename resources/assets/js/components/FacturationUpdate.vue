<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-body panel-colloque">

                <span v-if="facturation.company"><strong>{{ facturation.company }}</strong></span>
                <span v-if="facturation.civilite">{{ facturation.civilite }}</span>
                <span>{{ facturation.name }}</span>
                <span v-if="facturation.cp">{{ facturation.cp }}</span>
                <span v-if="facturation.complement">{{ facturation.complement }}</span>
                <span>{{ facturation.adresse }}</span>
                <span>{{ facturation.npa }} {{ facturation.ville }}</span>

                <button type="button" @click="show" class="btn btn-danger btn-xs pull-right">changer</button>
            </div>
        </div>
        <modal name="example"
               :width="300"
               :height="300"
               @before-open="beforeOpen"
               @before-close="beforeClose">
            sdfgh
        </modal>
    </div>
</template>

<script>
    import VModal from 'vue-js-modal';

    export default {
        props: ['facturation'],
        components: {modal:VModal},
        data () {
            return {
                url: location.protocol + "//" + location.host+"/",
                change:false,
                changed:false,
                facturation_detail:'sdfg',
                adresse_facturation : this.facturation ? this.facturation : null,
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            show () {
                this.$modal.show('example');
            },
            hide () {
                this.$modal.hide('example');
            },
            beforeOpen (event) {
                console.log(event)
                // Set the opening time of the modal
                this.time = Date.now()
            },
            beforeClose (event) {
                console.log(event)
                // If modal was open less then 5000 ms - prevent closing it
                if (this.time + this.duration < Date.now()) {
                    event.stop()
                }
            }
        }
    }
</script>
