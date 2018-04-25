
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

Vue.component('generate', require('./components/Generate.vue'));
Vue.component('rappel', require('./components/Rappel.vue'));
Vue.component('organisateur', require('./components/Organisateur.vue'));
Vue.component('endroit', require('./components/Endroit.vue'));
Vue.component('jurisprudence', require('./components/Jurisprudence.vue'));
Vue.component('occurrence', require('./components/Occurrence.vue'));
Vue.component('price', require('./components/Price.vue'));
Vue.component('option-groupe', require('./components/OptionGroupe.vue'));
Vue.component('detenteur', require('./components/Detenteur.vue'));

Vue.component('manager', require('./components/Manager.vue'));
Vue.component('filter-adresse', require('./components/FilterAdresse.vue'));
Vue.component('list-autocomplete', require('./components/ListAutocomplete.vue'));
Vue.component('build', require('./components/Build.vue'));
Vue.component('edit-build', require('./components/EditBuild.vue'));
Vue.component('build-newsletter', require('./components/BuildNewsletter.vue'));
Vue.component('build-newsletter-models', require('./components/BuildNewsletterModels.vue'));
Vue.component('build-newsletter-group', require('./components/BuildNewsletterGroup.vue'));
Vue.component('analyse-newsletter', require('./components/partials/AnalyseNewsletter.vue'));
Vue.component('image-newsletter', require('./components/partials/ImageNewsletter.vue'));

import VueDragAndDropList from 'vue-drag-and-drop-list';

Vue.use(VueDragAndDropList);

const app = new Vue({
    el: '#appComponent'
});



