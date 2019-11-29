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

import ProfileComponent from './components/Profile.vue';
const profile = Vue.component('profile', ProfileComponent);

 const routes = [
     { path: '/', redirect: '/home'},
     { path: '/home', component: home},
     { path: '/login', component: login,  meta:{ requiresVisitor: true } },
     { path: '/logout', component: logout, meta:{ requiresAuth: true } },
     { path: '/profile', component: profile, meta:{ requiresAuth: true } },
 ];

 const router = new VueRouter({
     routes:routes
 });

 router.beforeEach((to, from, next) =>{
    if(to.matched.some(record => record.meta.requiresAuth)){
        if(!sessionStorage.getItem('user')){
        next({
            path: '/login'
        });
        } else {
            next();
        }
    } else if(to.matched.some(record => record.meta.requiresVisitor)){
        if(store.state.user){
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

    },
    created() {
        console.log('-----');
        console.log(this.$store.state.user);
        this.$store.commit('loadTokenAndUserFromSession');
        console.log(this.$store.state.user);
    }
}).$mount('#app');
