<template>
    <div>
        <div class="jumbotron">
                <h1>{{ title }}</h1>               
        </div>
         <h3>There are currently {{ wallets }} Virtual Wallets! {{ teste }}</h3>
    </div>
</template>

<script>
    export default {
        data: function(){
            return{
                title: 'Welcome To Virtual Wallet!',
                wallets: 0,
                teste: null
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