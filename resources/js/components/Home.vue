<template>
    <div>
        <div class="jumbotron">
                <h1>{{ title }}</h1>               
        </div>
        <h3>There are currently {{ wallets }} Virtual Wallets!</h3>

        <br>

        <div v-if="this.$store.state.user != null">
            <div v-show="this.$store.state.user.type == 'o'">
                <button type="button" class="btn btn-primary" v-on:click.prevent="registerCredit()">Register Credit</button>
            </div>
        </div>

        <br>

        <div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

        <div class="alert alert-danger" v-if="showError">			 
			<button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

        <br>

        <register-credit @credit-canceled="cancelCredit" @email-error="emailError" @credit-created="creditCreated" v-if="showRegisterCredit"></register-credit>
        
        

    </div>


</template>

<script>
    import RegisterCredit from './RegisterCredit.vue';

    export default {
        data: function(){
            return{
                title: 'Welcome To Virtual Wallet!',
                wallets: 0,
                showRegisterCredit: false,
                showSuccess: false,
                showError: false,
                successMessage: "",
            }
        },

        methods: {
            registerCredit: function(){
                this.showRegisterCredit = true;
            },
            cancelCredit: function(){
                this.showRegisterCredit = false;
            },
            emailError: function(){
                this.showError = true;
                this.successMessage = "Email does not exist!";
            },
            creditCreated: function(){
                this.showSuccess = true;
                this.successMessage = "Credit created with success";
                this.showRegisterCredit = false;
            }
        },

        mounted() {
            axios.get('api/home')
                .then(response=>{
                    this.wallets = response.data;
                });
        },

         components: {
	    	'register-credit': RegisterCredit
	    },       
    }
</script>
<style scoped>

</style>