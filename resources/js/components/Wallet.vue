<template>
    <div>
        <div class="jumbotron">
                <h1>Balance: {{ balance }}€</h1>               
        </div>
      
        <div>
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
                    <tr v-for="movement in movements.data"  :key="movement.id" :class="{activerow: selectedMovement === movement}">
                        <td>{{ movement.id }}</td>
                        <td>{{ movement.type }}</td>
                            <td v-if="movement.transfer_wallet_id != undefined">{{ movement.transfer_wallet.email }}</td>
                            <td v-if="movement.transfer_wallet_id == null"> </td>
                        <td>{{ movement.type_payment }}</td>
                            <td v-if="movement.category">{{ movement.category.name }}</td>
                            <td v-if="!movement.category"> </td>
                        <td>{{ movement.date }}</td>
                        <td>{{ movement.start_balance }}</td>
                        <td>{{ movement.end_balance }}</td>
                        <td>{{ movement.value }}</td>
                        <td>                    
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="movementDetais(movement)">Details</button>
                        </td>
                    </tr>            
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <pagination :data="movements" :limit=4 @pagination-change-page="getResults"></pagination>
        </div>

        <movement-details :movement="selectedMovement" @details-canceled="cancelMovementDetails" v-if="selectedMovement"></movement-details>
    </div>


</template>

<script>
    import MovementDetailsComponent from "./MovementDetails.vue";

    export default {
        data: function(){
            return{
                title: 'Balance: ',
                user: this.$store.state.user,
                movements: {},
                selectedMovement: null,
                pagination: [],
                balance: "",
            }            
        },

        methods: {
           getMovements: function(){
                axios.get('api/movements/'+this.user.id)
                    .then(response=>{
                        this.movements = response.data;
                    })
                    .catch(error => {                        
                        console.log(error);
                    })
            },
            getBalance: function(){
                axios.get('api/wallet/'+this.user.id+'/balance')
                    .then(response=>{
                        this.balance = response.data;
                    })
                    .catch(error => {                        
                        console.log(error);
                    })
            },
            getResults:function(page = 1){
                axios.get('api/movements/' + this.user.id + "?page=" + page)
                    .then(response=>{
                        this.movements = response.data;
                    })
            },
            movementDetais: function(movement){
                this.selectedMovement = movement;
            },           
            cancelMovementDetails: function(){
                this.selectedMovement = null;
            },
        },

        components: {
            "movement-details": MovementDetailsComponent,
        },
        
        mounted() {
            this.getBalance();
            this.getMovements();
        }
    }
</script>
<style scoped>
tr.activerow {
  background: #123456 !important;
  color: #fff !important;
}
</style>