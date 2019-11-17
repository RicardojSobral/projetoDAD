<template>
    <div>
        <div class="login-form1">
            <h2 class="login-heading1">Login</h2>
            <form action="#" >

                <div class="form-control1">
                    <label name="label1" for="email">Email</label>
                    <input type="email" name="email" id="email" class="login-input1" v-model="email">
                </div>

                 <div class="form-control1">
                    <label name="label1" for="password">Password</label>
                    <input type="password" name="password" id="password" class="login-input1" v-model="password">
                </div>

                <div class="form-control1">
                    <button type="submit" class="btn-submit">Login</button>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
    export default {

      name: 'login',

        data: function(){
            return{
                email: '',
                password: '',
            }
        },
        methods: {
          login(){
           axios.post('./api/login', {
                email: email,
                password: password,
              })
                .then(response => {
                  console.log();
                  /*const token = response.data.access_token;
      
                  localStorage.setItem('access_token', token);
                  context.commit('retrieveToken', token);
                  resolve(response);*/
                })
                .catch(error => {
                  console.log(error);
                  //reject(error);
                });
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