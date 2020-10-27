<template>
    <div>

       <form id="multiplpeForm" :action="url + path" method="post">

       <div id="invoice_for"></div>

        <p><a class="btn btn-sm btn-info" @click="add"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>

            <input type="hidden" name="_token" :value="_token">
            <input type="hidden" name="colloque_id" :value="colloque.id">
            <input type="hidden" name="user_id" :value="user_id">
            <input type="hidden" name="type" :value="form">

            <div v-for="(participant,participant_id) in participants">
                <fieldset class="field_clone">

                    <p class="text-right"><button type="button" @click="remove(participant_id)" class="btn btn-sm btn-danger">x</button></p>

                    <div class="form-group">
                        <label>Nom du participant</label>
                        <input name="participant[]" v-model="participant.name" required class="form-control participant-input" value="" type="text">
                        <p class="text-muted">Inscrire "prenom, nom"</p>
                    </div>

                    <div class="form-group">
                        <label>Email (lier à un compte)</label>
                        <input name="email[]" class="form-control" v-model="participant.email" value="" type="text">
                    </div>

                    <option-link
                        :form="form"
                        :participant_id="participant_id"
                        :colloque="colloque"
                        :prices="prices"
                        :pricelinks="pricelinks"></option-link>
                </fieldset>
            </div>


        {{ formData ? unserialize(formData) : formData }}

        <div class="clearfix"></div>
        <hr>

        <p class="text-right"><button class="btn btn-danger" id="submitAll" @click="validate($event)" type="button">Inscrire</button></p>

       </form>
    </div>
</template>

<script>
    import OptionLink from './OptionLink.vue';

    export default {
        props: ['colloque','prices','pricelinks','form','_token','participant_id','user_id'],
        data() {
            return {
                participants:[{
                    'name' : '',
                    'email' : '',
                }],
                inValidation:false,
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
        watch: {},
        methods: {
            getInfo(){
                axios.post(this.url + 'admin/inscription/registerinfos',{user_id :this.user_id, colloque_id: this.colloque.id }).then(function (response) {
                  console.log(response.data);
                  $('#invoice_for').empty().append(response.data);
                }).catch(function (error) { console.log(error);});
            },
            add(){
                this.participants.push({
                    'name' : '',
                    'email' : '',
                    'colloques' : [],
                    'options' : [],
                });
            },
            remove(index) {
                this.participants.splice(index,1)
            },
            validate(event){
                this.inValidation = true;

                let valid = $("#multiplpeForm").valid();

                if(valid){
                    $('#multiplpeForm').submit();

                    axios.post(this.url + this.path,formData).then(function (response) {
                        console.log(response.data);
                    }).catch(function (error) { console.log(error);});
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
