<template>
    <div>
        <form id="simpleForm" :action="url + path" method="post">

           <div id="invoice_for"></div>

            <input type="hidden" name="_token" :value="_token">
            <input type="hidden" name="colloque_id" :value="colloque.id">
            <input type="hidden" name="user_id" :value="user_id">
            <input type="hidden" name="type" :value="form">

            <option-link
                    :form="form"
                    :colloque="colloque"
                    :prices="prices"
                    :pricelinks="pricelinks"></option-link>
        </form>

      <div class="clearfix"></div>
      <hr>

      <p class="text-right"><button class="btn btn-danger" id="submitAll" @click="validate($event)" type="button">Inscrire</button></p>

    </div>
</template>

<script>
    import OptionLink from './OptionLink.vue';

    export default {
        props: ['colloque','prices','pricelinks','form','_token','participant_id','user_id'],
        data() {
            return {
                formData:null,
                path: 'admin/inscription',
                url: location.protocol + "//" + location.host+"/",
            }
        },
        components:{
            'option-link' : OptionLink
        },
        mounted: function () {
          this.getInfo();
        },
        methods: {
            getInfo(){
              axios.post(this.url + 'admin/inscription/registerinfos',{user_id :this.user_id, colloque_id: this.colloque.id, type: 'simple' }).then(function (response) {
                console.log(response.data);
                $('#invoice_for').empty().append(response.data);
              }).catch(function (error) { console.log(error);});
            },
            validate(event){
                this.inValidation = true;

                let valid = $("#simpleForm").valid();
                this.formData = $("#simpleForm").serialize();

                axios.post(this.url + 'vue/participant', this.formData).then(function (response) {
                    console.log(response.data);
                }).catch(function (error) { console.log(error);});

                if(valid){
                   $('#simpleForm').submit();
                }
            },
            unserialize: function(serialize) {
                let obj = {};
                var serialize = serialize.split('&');
                for (let i = 0; i < serialize.length; i++) {
                    var thisItem = serialize[i].split('=');
                    obj[decodeURIComponent(thisItem[0])] = decodeURIComponent(thisItem[1]);
                };
                return obj;
            }
        }
    }
</script>
