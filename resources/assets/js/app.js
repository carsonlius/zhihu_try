
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import 'social-share.js/src/js/social-share.js';

// 引入Vue树形结构
import 'vue-tree-halower/dist/halower-tree.min.css';
import { VTree, VSelectTree } from 'vue-tree-halower';
Vue.use (VTree);
Vue.use (VSelectTree);

// 引入vue-easytable
// import css
import 'vue-easytable/libs/themes-base/index.css'

// import table and pagination comp
import {VTable,VPagination} from 'vue-easytable'

// Register to global
Vue.component(VTable.name, VTable);
Vue.component(VPagination.name, VPagination);

// 引入省市区组件
import 'vue-area-linkage/dist/index.css'; // v2 or higher
import VueAreaLinkage from 'vue-area-linkage';
Vue.use(VueAreaLinkage);

// 引入modal组件
import SweetModal from 'sweet-modal-vue/src/plugin.js';
Vue.use(SweetModal);

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

// 上传文件的组件
const VueUploadComponent = require('vue-upload-component');
Vue.component('file-upload', VueUploadComponent);
Vue.filter('formatSize', function (size) {
    if (size > 1024 * 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024 / 1024).toFixed(2) + ' TB'
    } else if (size > 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB'
    } else if (size > 1024 * 1024) {
        return (size / 1024 / 1024).toFixed(2) + ' MB'
    } else if (size > 1024) {
        return (size / 1024).toFixed(2) + ' KB'
    }
    return size.toString() + ' B'
});

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

// 公用的模态框
Vue.component('prompt-modal', require('./components/PromptModal'));

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

// 用户私信通话列表
Vue.component('inbox-list', require('./components/InboxDetail'));

// 通知组件
Vue.component('bell-notification', require('./components/BellNotification'));

// 上传头像组建
Vue.component('avatar-user', require('./components/AvatarUser'));

// 用户设置组件
Vue.component('setting-user', require('./components/UserSetting'));

// 用户信息组件
Vue.component('info_user', require('./components/UserInfo'));

// 新建角色
Vue.component('role-create', require('./components/RoleCreate'));

// 角色列表
Vue.component('role-list', require('./components/RoleList'));

// 编辑角色
Vue.component('role-edit', require('./components/RoleEdit'));

// 新建权限
Vue.component('permission-create', require('./components/PermissionCreate'));

// 权限列表
Vue.component('permission-list', require('./components/PermissionList'));

// 权限编辑
Vue.component('permission-edit', require('./components/PermissionEdit'));

// 树形结构测试
Vue.component('tree', require('./components/Tree'));

// 角色权限分配
Vue.component('role-permission', require('./components/RolePermission'));

// 用户角色分配
Vue.component('user-role-list', require('./components/UserRoleList'));

// 用户角色编辑
Vue.component('user-role-edit', require('./components/UserRoleEdit'));

// 期刊列表页面
Vue.component('periodical-list', require('./components/PeriodicalList'));

// 期刊创建页面
Vue.component('periodical-create', require('./components/PeriodicalCreate'));

//  单个文件上传的组件
Vue.component('file-single-uploader', require('./components/UploadSingleFile'));

// 期刊编辑界面
Vue.component('periodical-edit',require('./components/PeriodicalEdit') );

// 更新音乐期刊
Vue.component('periodical-music-edit',require('./components/PeriodicalMusicEdit') );

const app = new Vue({
    el: '#app',
});
