
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('es6-promise/auto');
require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));
//Vue.component('inscription', require('./components/Inscription.vue'));
// Vue.component('content-form', require('./components/ContentForm.vue'));

Vue.component('generate', require('./components/Generate.vue').default);
Vue.component('rappel', require('./components/Rappel.vue').default);
Vue.component('organisateur', require('./components/Organisateur.vue').default);
Vue.component('endroit', require('./components/Endroit.vue').default);
Vue.component('jurisprudence', require('./components/Jurisprudence.vue').default);
Vue.component('occurrence', require('./components/Occurrence.vue').default);
Vue.component('price', require('./components/Price.vue').default);
Vue.component('price-link', require('./components/PriceLink.vue').default);
Vue.component('option-groupe', require('./components/OptionGroupe.vue').default);
Vue.component('detenteur', require('./components/Detenteur.vue').default);

Vue.component('manager', require('./components/Manager.vue').default);
Vue.component('image-uploader', require('./components/ImageUploader.vue').default);
Vue.component('filter-adresse', require('./components/FilterAdresse.vue').default);
Vue.component('list-autocomplete', require('./components/ListAutocomplete.vue').default);

Vue.component('build', require('./components/Build.vue').default);
Vue.component('edit-build', require('./components/EditBuild.vue').default);

Vue.component('build-newsletter', require('./components/BuildNewsletter.vue').default);
Vue.component('build-newsletter-models', require('./components/BuildNewsletterModels.vue').default);
Vue.component('build-newsletter-group', require('./components/BuildNewsletterGroup.vue').default);
Vue.component('analyse-newsletter', require('./components/partials/AnalyseNewsletter.vue').default);
Vue.component('image-newsletter', require('./components/partials/ImageNewsletter.vue').default);

Vue.component('create-bloc', require('./components/CreateBloc.vue').default);
Vue.component('edit-bloc', require('./components/EditBloc.vue').default);

Vue.component('product-select', require('./components/ProductSelect.vue').default);
Vue.component('facturation-adresse', require('./components/FacturationAdresse.vue').default);
Vue.component('adresse-update', require('./components/AdresseUpdate.vue').default);
Vue.component('choix-duplicate', require('./components/ChoixDuplicate.vue').default);
Vue.component('rabais', require('./components/Rabais.vue').default);
Vue.component('tags', require('./components/Tags.vue').default);
Vue.component('option-link', require('./components/OptionLink.vue').default);
Vue.component('participant', require('./components/Participant.vue').default);
Vue.component('register-simple', require('./components/RegisterSimple.vue').default);
Vue.component('question-row', require('./components/QuestionRow.vue').default);
Vue.component('create-model', require('./components/CreateModel.vue').default);

import VueDragAndDropList from 'vue-drag-and-drop-list';
Vue.use(VueDragAndDropList);

import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
Vue.use(VueFormWizard)

import vmodal from 'vue-js-modal';
Vue.use(vmodal);

import VueLazyload from 'vue-lazyload'
Vue.use(VueLazyload)

const app = new Vue({
    el: '#appComponent'
});

const appVue = new Vue({
    el: '#appVue',
    methods: {
        onComplete:function(){
            return true;
        },
        beforeTabSwitch: function(){

            let $form = $('#inscriptionForm');
            let validator = $form.validate({
                errorPlacement: function( label, element ) {
                    label.insertBefore( element );
                }
            });

            return $form.find('input').valid();
        },
        beforeTabSwitchPrices: function(){

            let $form = $('#inscriptionForm');
            let $radios = $form.find(".item_wrapper_link");

            $radios.each(function(groupe){
                let checked = $(this).find('input[type="radio"]:checked').val();
                if(checked){

                    let colloque = $(this).data('colloque');
                    let id       = $(this).data('id');

                    axios.post(location.protocol + "//" + location.host + "/" + 'pubdroit/colloqueoptions',{colloque_id : colloque, price_link_id : id}).then(function (response) {
                        console.log(response.data);
                        $('#colloque_options_wrapper').empty().append(response.data);
                    }).catch(function (error) { console.log(error);});
                }
                else{
                    $('#colloque_options_wrapper').empty();
                }
            });

            console.log($radios);

            let validator = $form.validate({
                errorPlacement: function( label, element ) {label.insertBefore( element );}
            });

            return $form.find('input').valid();

        },
        lastTabResume: function(){
            let $form = $('#inscriptionForm');

            axios.post(location.protocol + "//" + location.host + "/" + 'pubdroit/colloque/inscription/resume', $form.serialize()).then(function (response) {
                console.log(response.data);
                $('#resumeWrapper').empty().append(response.data);
            }).catch(function (error) { console.log(error);});

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
});
