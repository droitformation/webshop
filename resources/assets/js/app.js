
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

import VueDragAndDropList from 'vue-drag-and-drop-list';

Vue.use(VueDragAndDropList);

const app = new Vue({
    el: '#appComponent'
});



