
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// 引入字体图标
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCoffee } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
library.add(faCoffee);
Vue.component('font-awesome-icon', FontAwesomeIcon);

// 引入表单验证插件
import zh from 'vee-validate/dist/locale/zh_CN';
import VeeValidate,{ Validator } from 'vee-validate';
// Localize takes the locale object as the second argument (optional) and merges it.
Validator.localize('zh', zh);
Vue.use(VeeValidate);

// 引入vue-select组件
import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

// 引入vue-rsource
let VueResource = require('vue-resource');
Vue.use(VueResource);

let token = document.head.querySelector('meta[name="csrf-token"]');
let api_token = document.head.querySelector('meta[name="api-token"]');
if (token) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

if (api_token) {
    Vue.http.headers.common['Authorization'] = api_token.content;
} else {
    console.error('api token not found');
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

// 问题关注组件
Vue.component('question-follow-button', require('./components/QuestionFollowButton'));

// 用户互相关注组件
Vue.component('user-follow-button', require('./components/UserFollowButton'));

// 关注组件
Vue.component('user-vote-button', require('./components/UserVoteButton'));

// 私信组件
Vue.component('send-message-button', require('./components/SendMessage'));

// 评论组件
Vue.component('comment-button', require('./components/Comment'));

// 私信通知
Vue.component('bell-message', require('./components/BellMessage'));

const app = new Vue({
    el: '#app',
    data : {

    },
});
