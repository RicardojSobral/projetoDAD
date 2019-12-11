<template>
    <div>   
        <div class="alert alert-danger" v-if="showError">			 
			<button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
			<strong>{{ errorMessage }}</strong>
		</div>

        <div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

        <div class="row">
            <div class="col-md-3">            
                <div class="form-group">
                    <input type="text" name="name" class="form-control"  placeholder="Search by user name" v-model="search.name">               
                </div> 
                <div class="form-group">
                    <select name="type" class="form-control" v-model="search.type">
                        <option value='' selected> -- Type Of User -- </option>
                        <option value="a" >Administrator</option>
                        <option value="o" >Operator</option>
                        <option value="u" >Platform User</option>
                    </select>            
                </div> 
            </div> 
            <div class="col-md-3"> 
                <div class="form-group">
                    <input type="text" name="email" class="form-control" placeholder="Search by e-mail" v-model="search.email">               
                </div>
                <div class="form-group">
                    <select name="active" class="form-control" v-model="search.active">
                        <option value='' selected> -- Status Of Platform User -- </option>
                        <option value="1" >Active</option>
                        <option value="0" >Inactive</option>
                    </select>            
                </div>                
            </div>
            <span class="form-group-btn">
                <button type="submit" class="btn btn-primary" v-on:click="getUsers()">Search</button>
            </span>       
        </div>

        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data"  :key="user.id">
                        <td v-if="user.photo"><img v-bind:src="'storage/fotos/' + user.photo" style="width:75px; height:75px; border-radius:50%;"></td>
                        <td v-if="!user.photo"><img v-bind:src="'storage/fotos/unknown.jpg'" style="width:75px; height:75px; border-radius:50%;"></td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.type }}</td>

                        <td v-if="user.type == 'u' && user.active==1">Active</td>
                        <td v-if="user.type == 'u' && user.active==0">Inactive</td>
                        <td v-if="user.type != 'u'"> </td>

                        <td v-if="user.type == 'u' && user.wallet.balance != 0">Has Money</td>  <!--empty/hasCash-->
                        <td v-if="user.type == 'u' && user.wallet.balance == 0">Empty</td>  <!--empty/hasCash-->
                        <td v-if="user.type != 'u'"> </td> 
                        <td>                    
                            <button type="button" class="btn btn-sm btn-danger" v-on:click="deleteAdmin(user)"  v-if="user.type != 'u' && user.id != currentUser.id">Delete</button>
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="activateUser(user)"  v-if="user.type == 'u' && user.active==0">Activate</button>
                            <button type="button" class="btn btn-sm btn-secondary" v-on:click="deactivateUser(user)"  v-if="user.type == 'u' && user.active==1">Deactivate</button>
                        </td>
                    </tr>            
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <pagination :data="users" :limit=4 @pagination-change-page="getResults"></pagination>
        </div>
        
    </div>
</template>

<script>
    export default {

        data: function(){
            return{
                currentUser: this.$store.state.user,
                users: {},
                pagination: [],
                search:{
                    name:'',
                    type: '',
                    email: '',
                    active: '',
                },
                showError: false,
                showSuccess: false, 
                successMessage: '',
                errorMessage: '',
                
            }            
        },

        methods: {
            getUsers: function(){
                axios.post('api/users/filter', this.search)
                .then(response => {
                    if(response.data == "Can't search by status and type if type is different form 'Platform User'!"){
                        this.showError = true;
                        this.errorMessage = response.data;
                    }else{
                        this.users = response.data;
                    }
                })
                .catch(error => {                        
                    console.log(error);
                })
            },
            deleteAdmin: function(user){
                axios.delete('api/users/'+user.id)
                .then(response=>{
                    this.getUsers();
                    this.successMessage = "User removed with success!";
                    this.showSuccess = true;
                })
                .catch(error => {                        
                    console.log(error);
                })
            },
            activateUser: function(user){
                axios.put('api/users/activate/'+user.id)
                .then(response => {
                    this.getUsers();
                    this.successMessage = "User activated with success!";
                    this.showSuccess = true;
                })
                .catch(error => {                        
                    console.log(error);
                })
            },
            deactivateUser: function(user){
                axios.put('api/users/deactivate/'+user.id)
                .then(response => {
                   if(response.data == "Wallet Balance must be 0.00 to deactivate a user!"){
                       this.errorMessage = response.data;
                       this.showError = true;
                   }else{
                       this.getUsers();
                       this.successMessage = "User deactivated with success!";
                       this.showSuccess = true;
                   }
                })
                .catch(error => {                        
                    console.log(error);
                })
            },
            getResults:function(page = 1){
                axios.post('api/users/filter?page=' + page, this.search)
                    .then(response=>{
                        this.users = response.data;
                    })
            },
        },

        mounted() {
            this.getUsers();
        }
    }
</script>
