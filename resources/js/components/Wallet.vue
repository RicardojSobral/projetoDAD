<template>
    <div>
        <div class="jumbotron">
                <h1>Balance: {{ balance }}€</h1>               
        </div>

        <div class="row">
            <div class="col-md-3">            
                <div class="form-group">
                    <input type="text" name="id" class="form-control"  placeholder="Search by movement ID" v-model="search.id">               
                </div> 
                <div class="form-group">
                    <select name="type" class="form-control" v-model="search.type">
                        <option value='' selected> -- Type Of Movement -- </option>
                        <option value="e" >Expense</option>
                        <option value="i" >Income</option>
                    </select>            
                </div> 
            </div> 
            <div class="col-md-3"> 
                <div class="form-group">
                    <input type="text" name="category" class="form-control" placeholder="Search Category" v-model="search.category">               
                </div>
                <div class="form-group">
                    <select name="type_payment" class="form-control" v-model="search.type_payment">
                        <option value='' selected> -- Type Of Payment -- </option>
                        <option value="c" >Cash</option>
                        <option value="bt" >Bank Transfer</option>
                        <option value="mb" >MB Payment</option>
                    </select>            
                </div>                
            </div>
            <div class="form-group">
                <input type="text" name="transfer_email" class="form-control" placeholder="Search Transfer e-mail" v-model="search.transfer_email">               
            </div> 
            <div class="col-md-3"> 
                <div class="form-group">
                    <input type="text" name="data_sup" class="form-control" placeholder="Date Superior To (yyyy-mm-dd)" v-model="search.data_sup">               
                </div>
                <div class="form-group">
                    <input type="text" name="data_inf" class="form-control" placeholder="Date Inferior To (yyyy-mm-dd)" v-model="search.data_inf">               
                </div>               
            </div>
            <span class="form-group-btn">
                <button type="submit" class="btn btn-primary" v-on:click="getFilteredMovements()">Search</button>
            </span>       
        </div>

        <div class="alert alert-danger" v-if="showError">			 
			<button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
			<strong>{{ successMessage }}</strong>
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
                search:{
                    user_id: this.$store.state.user.id,
                    id: '',
                    type: '',
                    category: '',
                    type_payment: '',
                    transfer_email: '',
                    data_inf: '',
                    data_sup: '',
                },
                showError: false,
                showSuccess: '',
    
            }            
        },

        methods: {
            getFilteredMovements: function(){
                axios.post('api/movements/filter', this.search)
                    .then(response=>{
                        if(response.data == 'E-mail does not exist!'){
                            this.showError = true;
                            this.successMessage = response.data;
                        }else if(response.data == 'Category does not exist!'){
                            this.showError = true;
                            this.successMessage = response.data;
                        }else{
                            this.movements = response.data;
                        }                        
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
                axios.post('api/movements/filter?page=' + page, this.search)
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
            this.getFilteredMovements();
        }
    }
</script>
<style scoped>
tr.activerow {
  background: #123456 !important;
  color: #fff !important;
}
</style>