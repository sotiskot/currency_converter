/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const $ = require('jquery');

require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

// importing vue, making vue components and initializing the #app element
import Vue from 'vue'

window.Vue = require('vue');

Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('edit-component', require('./components/EditComponent.vue').default);

const app = new Vue({
    el: '#app',
});

/**
 *  Loading and using axios HTTP library which allows us for ease of use HTTP requests with the 
 *  back-end in place of Ajax
 */
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss'; // Trying to add bootstrap through sass, unfortunantely couldn't make it to work.