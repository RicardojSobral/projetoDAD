/*jshint esversion: 6 */
require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import HomeComponent from './components/Home.vue';
const home = Vue.component('home', HomeComponent);

import AccountComponent from './components/accountCreate.vue';
const accountCreate = Vue.component('accountCreate', AccountComponent);

 const routes = [
     { path: '/', redirect: '/home'},
     { path: '/home', component: home },
     { path: '/accountcreate', component: accountCreate}
 ];

 const router = new VueRouter({
     routes:routes
 });

const app = new Vue({
    el: '#app',
    router,
    data:{
        currentUser: null,
    },
    methods: {
         /*createUser: function () {
            axios.post('api/users/', this.currentUser)
                .then(response => {
                    this.showSuccess = true;
                    this.successMessage = 'User Registed';
                    this.currentUser = null;
                })
         },*/
         /*cancelForm: function () {

         }*/
    }

}).$mount('#app');
