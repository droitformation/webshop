
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('generate', require('./components/Generate.vue'));
Vue.component('rappel', require('./components/Rappel.vue'));
Vue.component('organisateur', require('./components/Organisateur.vue'));
Vue.component('endroit', require('./components/Endroit.vue'));
Vue.component('jurisprudence', require('./components/Jurisprudence.vue'));
Vue.component('occurrence', require('./components/Occurrence.vue'));
Vue.component('price', require('./components/Price.vue'));
Vue.component('option-groupe', require('./components/OptionGroupe.vue'));
Vue.component('inscription', require('./components/Inscription.vue'));
Vue.component('detenteur', require('./components/Detenteur.vue'));
Vue.component('content-form', require('./components/ContentForm.vue'));

Vue.component('manager', require('./components/Manager.vue'));

const app = new Vue({
    el: '#appComponent'
});
