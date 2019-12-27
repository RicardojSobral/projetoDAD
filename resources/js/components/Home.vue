<template>
    <div>
        <div class="jumbotron">
                <h1>{{ title }}</h1>
        </div>
        <h3>There are currently {{ wallets }} Virtual Wallets!</h3>

        <br>

        <div v-if="this.$store.state.user != null">
            <div v-if="this.$store.state.user.type == 'o'">
                <button type="button" class="btn btn-primary" v-on:click.prevent="registerCredit()">Register Credit</button>
            </div>
        </div>

        <div v-if="this.$store.state.user != null">
            <div v-if="this.$store.state.user.type == 'o'">
                <button type="button" class="btn btn-primary" v-on:click.prevent="registerDebit()">Register Debit</button>
            </div>
        </div>

        <div v-if="this.$store.state.user != null">
            <div v-if="this.$store.state.user.type == 'a'">
                <button type="button" class="btn btn-primary" v-on:click.prevent="createAdminOp()">Create Admin/Operator</button>
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
        <register-debit @debit-canceled="cancelDebit" @email-error="emailError" @debit-created="debitCreated" v-if="showRegisterDebit"></register-debit>

        <create-admin @create-canceled="cancelAdmin" @admin-created="adminCreated" v-if="showCreateAdmin"></create-admin>

    </div>


</template>

<script>
    import RegisterCredit from './RegisterCredit.vue';
    import RegisterDebit from './RegisterDebit';
    import CreateAdmin from './CreateUserAdmin.vue';

    export default {
        data: function(){
            return{
                title: 'Welcome To Virtual Wallet!',
                wallets: 0,
                showRegisterCredit: false,
                showRegisterDebit: false,
                showSuccess: false,
                showError: false,
                successMessage: "",
                showCreateAdmin: false,
            }
        },

        methods: {
            registerCredit: function(){ //CREATE CREDIT METHODS
                this.showRegisterCredit = true;
            },
            cancelCredit: function(){
                this.showRegisterCredit = false;
            },
            registerDebit: function(){ //CREATE DEBIT METHODS
                this.showRegisterDebit = true;
            },
            cancelDebit: function(){
                this.showRegisterDebit = false;
            },
            emailError: function(){
                this.showError = true;
                this.successMessage = "Email does not exist!";
            },
            creditCreated: function(){
                this.showSuccess = true;
                this.successMessage = "Credit created with success";
                this.showRegisterCredit = false;
            },
            debitCreated: function(){
                this.showSuccess = true;
                this.successMessage = "Debit created with success";
                this.showRegisterDebit = false;
            },

            createAdminOp: function(){  // CREATE ADMIN/OPERATOR METHODS
                this.showCreateAdmin = true;
            },
            cancelCreateAdmin: function(){
                this.showCreateAdmin = false;
            },
            adminCreated: function(){
                this.showSuccess = true;
                this.successMessage = "Admin/Operator created with success";
                this.showCreateAdmin = false;
            }
        },

        mounted() {
            axios.get('api/home')
                .then(response=>{
                    this.wallets = response.data;
                });
        },

         components: {
             'register-credit': RegisterCredit,
             'register-debit': RegisterDebit,
             'create-admin': CreateAdmin
	    },
    }
</script>
<style scoped>

</style>
