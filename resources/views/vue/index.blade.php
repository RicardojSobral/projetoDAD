@extends('master')

@section('title', 'Vue.js App')

@section('content')

    <ul class="nav">
        <li><router-link to="/home">Home</router-link></li>

        <li ><router-link to="/profile" v-show="this.$store.state.user">Profile</router-link></li>
        
        <li ><router-link to="/login" v-show="!this.$store.state.user">Login</router-link></li>
       
        <li ><router-link to="/logout" v-show="this.$store.state.user">Logout</router-link></li>
       
    </ul>      
   
    <router-view></router-view>
@endsection
@section('pagescript')
<script src="js/app.js"></script>
@stop  
