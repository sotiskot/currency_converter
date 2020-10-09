/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const $ = require('jquery');

// importing vue, making vue components and initializing the #app element
import Vue from 'vue'

window.Vue = require('vue');

Vue.component('convert-component', require('./components/ConvertComponent.vue').default);
Vue.component('rates-component', require('./components/RatesComponent.vue').default);
Vue.component('currency-component', require('./components/CurrencyComponent.vue').default);

const app = new Vue({
    el: '#app',
});

/**
 *  Loading and using axios HTTP library which allows us for ease of use HTTP requests with the 
 *  back-end in place of Ajax
 */
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';