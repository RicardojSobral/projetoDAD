<template>
        <div class="login-form">
            <h2 class="login-heading">Login</h2>
            <form action="#" @submit.prevent="login">

                <div class="form-control">
                    <label name="label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="login-input" required v-model="email">
                </div>

                 <div class="form-control">
                    <label name="label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="login-input" required v-model="password">
                </div>

                <div class="form-control">
                    <button type="submit" class="btn-submit">Login</button>
                </div>

            </form>
        </div>
</template>

<script>
  export default {

    data(){
        return{
            email: '',
            password: '',
        }
    },
    methods: {
      login(){
        this.$store.dispatch('retrieveToken', {
            email: this.email,
            password: this.password,
        })
        .then(response => {
          this.$router.push('/');
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