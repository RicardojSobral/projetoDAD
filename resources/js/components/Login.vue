<template>
    <div>
        <div class="login-form">
            <h2 class="login-heading">Login</h2>
            <form action="#">

                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="login-input">
                </div>

                 <div class="form-control">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="login-input">
                </div>

                <div class="form-control">
                    <button type="submit" class="btn-submit">Login</button>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return{
                
            }
        },
        mounted() {
           
        }
    }
</script>
<style>

  
</style>