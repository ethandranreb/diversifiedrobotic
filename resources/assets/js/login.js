
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));
Vue.component('login', require('./components/login/LoginContainer.vue'));

const app = new Vue({
    el: '#login-container'
});

import './error-handling.js';
import './filter-handling.js';
import './regex.js';
import './jquery-confirm.min.js';
import './jquery-confirm.js';

(function () {
    var initialize = function () {
        var wh = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
        var h = document.getElementById('main-container').getBoundingClientRect()['height'];
        $('#main-container').css('margin-top', ((wh - h) / 2) + 'px');
    }

    $(document).ready( initialize() );
})();
