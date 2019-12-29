/*jshint esversion: 6 */
require('./bootstrap');

window.Vue = require('vue');

import VueSocketIO from "vue-socket.io";
Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://192.168.10.10:8080'
}));

import Toasted from "vue-toasted";
Vue.use(Toasted, {
    position: "bottom-center",
    duration: 5000,
    type: "info"
});

import { store } from './store/store';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import PaginationComponent from 'laravel-vue-pagination';
Vue.component('pagination', PaginationComponent);

import HomeComponent from './components/Home.vue';
const home = Vue.component('home', HomeComponent);

import LoginComponent from './components/Login.vue';
const login = Vue.component('login', LoginComponent);

import LogoutComponent from './components/Logout.vue';
const logout = Vue.component('logout', LogoutComponent);

import ProfileComponent from './components/Profile.vue';
const profile = Vue.component('profile', ProfileComponent);

import WalletComponent from './components/Wallet.vue';
const wallet = Vue.component('wallet', WalletComponent);

import ListAccountsComponent from './components/ListAccounts.vue';
const accounts = Vue.component('accounts', ListAccountsComponent);

import AccountComponent from './components/accountCreate.vue';
const accountCreate = Vue.component('accountCreate', AccountComponent);

import UserStatisticsComponent from './components/userStatistics.vue';
const userStatistics = Vue.component('userStatistics', UserStatisticsComponent);

import MovementDebitComponent from './components/RegisterDebit.vue';
const movementDebit = Vue.component('movementDebit', MovementDebitComponent);

 const routes = [
     { path: '/', redirect: '/home'},
     { path: '/home', component: home },
     { path: '/accountcreate', component: accountCreate, meta:{ requiresVisitor: true }},
     { path: '/login', component: login,  meta:{ requiresVisitor: true } },
     { path: '/logout', component: logout, meta:{ requiresAuth: true } },
     { path: '/profile', component: profile, meta:{ requiresAuth: true } },
     { path: '/wallet', component: wallet, meta:{ requiresAuth: true } },
     { path: '/accounts', component: accounts, meta:{ requiresAuth: true } },
     { path: '/userStatistics', component: userStatistics, meta:{ requiresAuth: true } },
     { path: '/movements/debit', component: movementDebit, meta: { requiresAuth: true } }, // ver caso
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
    sockets:{
        user_changed_income(dataFromServer) {
            this.$toasted.show(
                'An income movement of ' + dataFromServer + '€ was added to your wallet by an operator!'
            );
        },
        user_changed_transfer(dataFromServer) {
            this.$toasted.show(
                'User "' + dataFromServer[1] + '" transfered ' + dataFromServer[0] + '€ to your wallet!'
            );
        }
    },
    created() {
        console.log('-----');
        console.log(this.$store.state.user);
        this.$store.commit('loadTokenAndUserFromSession');
        if(this.$store.state.user){
            this.$socket.emit("user_enter", this.$store.state.user);
        }
        console.log(this.$store.state.user);
    }
}).$mount('#app');