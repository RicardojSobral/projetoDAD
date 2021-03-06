@extends('master')

@section('title', 'Vue.js App')

@section('content')

    <ul class="nav">
        <li><router-link to="/home">Home</router-link></li>

        <li v-if="this.$store.state.user"><router-link to="/userStatistics" v-show="this.$store.state.user.type == 'u'">Statistics</router-link></li>

        <li v-if="this.$store.state.user"><router-link to="/adminStatistics" v-show="this.$store.state.user.type == 'a'">Statistics</router-link></li>

        <li v-if="this.$store.state.user"><router-link to="/wallet" v-show="this.$store.state.user.type == 'u'">Wallet</router-link></li>

        <li v-if="this.$store.state.user"><router-link to="/accounts" v-show="this.$store.state.user.type == 'a'">Accounts</router-link></li>

        <li ><router-link to="/profile" v-show="this.$store.state.user">Profile</router-link></li>
        
        <li ><router-link to="/login" v-show="!this.$store.state.user">Login</router-link></li>

        <li ><router-link to="/accountcreate" v-show="!this.$store.state.user">Create Account</router-link></li>
       
        <li ><router-link to="/logout" v-show="this.$store.state.user">Logout</router-link></li>
       
    </ul>      
   
    <router-view></router-view>
@endsection
@section('pagescript')
<script src="js/app.js"></script>
@stop  
