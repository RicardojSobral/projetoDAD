<template>
    <div>
        <div class="jumbotron">
                <h1>{{ title }}</h1>               
        </div>
      
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Type</th>
                <th>Transfer E-mail</th>
                <th>Type Of Payment</th>
                <th>Category</th>
                <th>Date</th>
                <th>Start Balance</th>
                <th>End Balance</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="movement in movements"  :key="movement.id" :class="{active: selectedMovement === movement}">
                <td>{{ movement.id }}</td>
                <td>{{ movement.type }}</td>
                                                <td>{{ movement.transfer_wallet_id }}</td>
                <td>{{ movement.type_payment }}</td>
                                                <td>{{ movement.category_id }}</td>
                <td>{{ movement.date }}</td>
                <td>{{ movement.start_balance }}</td>
                <td>{{ movement.end_balance }}</td>
                <td>{{ movement.value }}</td>
                <td>                    
                    <a class="btn btn-sm btn-primary" v-on:click="movementDetais(movement)">Details</a>
                </td>
            </tr>
            
        </tbody>
    </table>

    </div>


</template>

<script>

    export default {
        data: function(){
            return{
                title: 'Wallet!',
                user: this.$store.state.user,
                movements: [],
                selectedMovement: null,
                pagination: [],
            }            
        },

        methods: {
           getMovements: function(){
                axios.get('api/movements/'+this.user.id)
                    .then(response=>{
                        this.movements = response.data.data;
                    })
                    .catch(error => {                        
                        console.log(error);
                    })
            },
            movementDetais: function(user){

            },
            makePagination:function(data){

            }
        },   
        
         mounted() {
            this.getMovements();
        }
    }
</script>
<style scoped>

</style>