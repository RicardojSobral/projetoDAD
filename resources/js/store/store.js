/*jshint esversion: 6 */
import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        token: localStorage.getItem('access_token') || null,
    },

    getters: {
      loggedIn(state){
        return state.token != null;
      }
    },

    mutations: {
      setToken(state, token){
        state.token = token;
      },
      deleteToken(state){
        state.token = null;
      }      
    },
   
    actions: {
      retrieveToken(context, credentials){

        return new Promise((resolve, reject) => {
          axios.post('api/login', {
            email: credentials.email,
            password: credentials.password,
          })
          .then(response => {
            const token = response.data.access_token;

            localStorage.setItem('access_token', token);
            context.commit('setToken', token);
            resolve(response);
          })
          .catch(error => {
            console.log(error);
            reject(error);
          });
        });           
      },

      destroyToken(context){
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;

        if(context.getters.loggedIn){
          return new Promise((resolve, reject) => {
            axios.post('api/logout')
            .then(response => {  
              localStorage.removeItem('access_token');
              context.commit('deleteToken');
              resolve(response);
            })
            .catch(error => {
              localStorage.removeItem('access_token');
              context.commit('deleteToken');
              reject(error);
            });
          });        
        }
      }

    }
});