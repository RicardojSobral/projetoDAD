@extends('master')

@section('title', 'Vue.js App')

@section('content')

    <ul class="nav">
        <li><router-link to="/home">Home</router-link></li>

        <li v-if="this.$store.state.user"><router-link to="/wallet" v-show="this.$store.state.user.type == 'u'">Wallet</router-link></li>

        <li ><router-link to="/profile" v-show="this.$store.state.user">Profile</router-link></li>
        
        <li ><router-link to="/login" v-show="!this.$store.state.user">Login</router-link></li>
       
        <li ><router-link to="/logout" v-show="this.$store.state.user">Logout</router-link></li>
       
    </ul>      
   
    <router-view></router-view>
@endsection
@section('pagescript')
<script src="js/app.js"></script>
@stop  
