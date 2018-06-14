
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// 引入vue-rsource
let VueResource = require('vue-resource');
Vue.use(VueResource);
// Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('button-show', require('./components/ButtonShow.vue'));
Vue.component('question-follow-button', require('./components/QuestionFollowButton.vue'));

const app = new Vue({
    el: '#app',
    data : {
      id : 'what happened'
    },
    // mounted: function () {
    //     console.log('Vue Instance');
    // },
    created: function () {
        // console.log('怎么没有相应');
    }
});
