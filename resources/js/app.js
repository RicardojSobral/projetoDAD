/*jshint esversion: 6 */
require('./bootstrap');

window.Vue = require('vue');

import { store } from './store/store';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import HomeComponent from './components/Home.vue';
const home = Vue.component('home', HomeComponent);

import LoginComponent from './components/Login.vue';
const login = Vue.component('login', LoginComponent);

import LogoutComponent from './components/Logout.vue';
const logout = Vue.component('logout', LogoutComponent);

 const routes = [
     { path: '/', redirect: '/home'},
     { path: '/home', component: home},
     { path: '/login', component: login,  meta:{ requiresVisitor: true }},
     { path: '/logout', component: logout, meta:{ requiresAuth: true } },
 ];

 const router = new VueRouter({
     routes:routes
 });

 router.beforeEach((to, from, next) =>{
    if(to.matched.some(record => record.meta.requiresAuth)){
        if(!store.getters.loggedIn){
        next({
            path: '/login'
        });
        } else {
            next();
        }
    } else if(to.matched.some(record => record.meta.requiresVisitor)){
        if(store.getters.loggedIn){
        next({
            path: '/home'
        });
        } else {
            next();
        }
    } else {
        next();
    }
 });

const app = new Vue({
     router,
     store,
    data:{

    }
}).$mount('#app');

export default {
    computed: {
        loggedIn(){
            return $store.getters.loggedIn;
        }
    }
};