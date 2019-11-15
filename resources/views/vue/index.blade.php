@extends('master')

@section('title', 'Vue.js App')

@section('content')

    <ul class="nav">
        <li><router-link to="/home">Home</router-link></li>
        <li><router-link to="/login">Login</router-link></li>
    </ul>   
    
   
    <router-view></router-view>
@endsection
@section('pagescript')
<script src="js/app.js"></script>
@stop  
