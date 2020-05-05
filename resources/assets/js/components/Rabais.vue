<template>

    <div class="rabais">
        <div class="colloque-coupon">
            <label class="rabais-info">
                <strong>Votre rabais</strong>
                <span v-if="isValid" class="text-success">{{ value }}</span>
            </label>
            <div class="input-group">
                <span v-if="isValid" class="input-group-addon input-group-addon-valid"><i class="fa fa-check text-success"></i></span>
                <input type="text" class="form-control" v-model="searchQuery" name="rabais" placeholder="XYZ">
                <span class="input-group-btn">
                    <button class="btn btn-default" @click="apply" type="button">Appliquer</button>
                </span>
            </div>
            <div v-if="isValid">
                <input type="hidden" name="rabais_id" :value="rabais_id">
            </div>
            <span v-if="isInvalid" class="invalid text-danger"><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp;{{ message }}</span>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['colloque_id'],
        data () {
            return {
                searchQuery: "",
                searchResult:false,
                message:'',
                value:'',
                isValid:null,
                isInvalid:null,
                rabais_id: null,
                url: location.protocol + "//" + location.host+"/",
            }
        },
        computed: {
            valideValue: function () {
                return this.searchResult.result ? 'Valide' : 'Ce rabais n\'est pas valide';
            }
        },
        methods: {
            apply: function() {

                let self = this;

                axios.get(this.url + 'vue/rabais/' + this.searchQuery + '/' + this.colloque_id).then(response => {
                    self.searchResult = response.data;
                    self.message = self.searchResult.message;
                    self.value   = self.searchResult.value;

                    if(self.searchResult.result){
                        self.isValid = true;
                        self.isInvalid = false;
                        self.rabais_id = response.data.rabais
                    }
                    else{
                        self.isInvalid = true;
                        self.isValid = false;
                    }
                });

            }
        }
    }
</script>
<style>
    .rabais{
        width: 60%;
    }

    .colloque-coupon{
        margin-top: 10px;
        padding: 10px;
        background: #fff;
        width: 100%;
    }

    .rabais-info{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .rabais-info strong,
    .rabais-info span{
        display: block;
    }

    span.invalid{
        color: #a72222;
        display:block;
        padding:7px 0 0 0;
    }

    .rabais-info span.valid{
        color: #18782e;
    }

    .input-group-addon-valid{
        border: none;
        background: #fff;
    }

</style>
