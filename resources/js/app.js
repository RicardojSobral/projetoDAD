/*jshint esversion: 6 */
require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import HomeComponent from './components/Home.vue';
const home = Vue.component('home', HomeComponent);

 const routes = [
     { path: '/', redirect: '/home'},
     { path: '/home', component: home },
 ];

 const router = new VueRouter({
     routes:routes
 });

const app = new Vue({
     router,
    data:{

    }
}).$mount('#app');