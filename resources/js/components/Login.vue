<template>
        <div class="login-form1">

            <div class="alert" :class="typeofmsg" v-if="showMessage">             
              <strong>{{ message }}</strong>
            </div>

            <h2 class="login-heading1">Login</h2>

                <div class="form-control1">
                    <label name="label1" for="email">Email</label>
                    <input type="email" name="email" id="email" class="login-input1" required v-model.trim="user.email">
                </div>

                 <div class="form-control1">
                    <label name="label1" for="password">Password</label>
                    <input type="password" name="password" id="password" class="login-input1" required v-model="user.password">
                </div>

                <div class="form-control1">
                    <button type="submit" class="btn-submit1" v-on:click.prevent="login">Login</button>
                </div>
        </div>
</template>

<script>
  export default {

    data(){
        return{
            user:{
                email: "",
                password: ""
            },
            typeofmsg: "alert-success",
            showMessage: false,
            message: "",
        }
    },
    methods: {
      login(){
        axios.post('api/login', this.user)
            .then(response => {
                this.$store.commit('setToken',response.data.access_token);
                return axios.get('api/users/me');
            })
            .then(response => {
                this.$store.commit('setUser',response.data.data);
                this.$router.push('/')
            })
            .catch(error => {
                this.$store.commit('clearUserAndToken');
                this.typeofmsg = "alert-danger";
                this.message = "Invalid credentials";
                this.showMessage = true;
                console.log(error);
            })
      }
    }
  }
</script>
<style lang="scss">
  .label1 {
    display: block;
    margin-bottom: 4px;
  }
  .login-heading1 {
    margin-bottom: 16px;
  }
  .form-control1 {
    margin-bottom: 24px;
  }
  .login-form1 {
    max-width: 500px;
    margin: auto;
  }
  .login-input1 {
    width: 100%;
    font-size: 16px;
    padding: 12px 16px;
    outline: 0;
    border-radius: 3px;
    border: 1px solid lightgrey;
  }
  .btn-submit1 {
    width: 100%;
    padding: 14px 12px;
    font-size: 18px;
    font-weight: bold;
    background: #60BD4F;
    color: white;
    border-radius: 3px;
    cursor: pointer;
    &:hover {
      background: darken(#60BD4F, 10%);
    }
  }
  
</style>