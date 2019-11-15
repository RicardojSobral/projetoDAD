<template>
    <div>
        <div class="jumbotron">
                <h1>{{ title }}</h1>
                <br>
               
        </div>
         <h3>There are currently {{ wallets }} Virtual Wallets!</h3>
    </div>
</template>

<script>
    export default {
        data: function(){
            return{
                title: 'Welcome To Virtual Wallet!',
                wallets: null
            }
        },
        mounted() {
            axios.get('api/home')
                .then(response=>{
                    this.wallets = response.data;
                });
        }
    }
</script>
<style scoped>

</style>