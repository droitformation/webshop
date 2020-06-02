<template>
    <div>
        <p><a class="btn btn-sm btn-info" @click="add"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>

        <div v-for="participant in participants">
            <fieldset class="field_clone">
                <div class="form-group">
                    <label>Nom du participant</label>
                    <input name="participant[]" v-model="participant.email" required class="form-control participant-input" value="" type="text">
                    <p class="text-muted">Inscrire "prenom, nom"</p>
                </div>

                <div class="form-group">
                    <label>Email (lier à un compte)</label>
                    <input name="email[]" class="form-control" value="" type="text">
                </div>

                <option-link
                        :optionLinkValidate="inValidation" @validated="handleValidated"
                        :form="form"
                        :colloque="colloque"
                        :prices="prices"
                        :pricelinks="pricelinks"></option-link>
            </fieldset>
        </div>

        <div class="clearfix"></div><br/>
        <button class="btn btn-danger" id="submitAll" @click="validate($event)" type="submit">Inscrire</button>
    </div>
</template>

<script>
    import OptionLink from './OptionLink.vue';

    export default {
        props: ['colloque','prices','pricelinks','form'],
        data() {
            return {
                participants:[{'email' : ''}],
                inValidation:false,
            }
        },
        components:{
            'option-link' : OptionLink
        },
        mounted: function () {},
        watch: {},
        methods: {
            add(){
                this.participants.push({'email' : ''});
            },
            validate(event){
                this.inValidation = true;

                //event.preventDefault();
            },
            handleValidated(event){
                //alert('ok ' + event);
            }
        }
    }
</script>
