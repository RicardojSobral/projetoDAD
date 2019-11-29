<template>
	<div class="jumbotron">
	    <h2>Change Password</h2>
	    <div class="form-group">
	        <label for="inputPassword">New Password:</label>
	        <input
	            type="password" class="form-control" v-model="password"
	            name="password" id="inputPassword" 
	            placeholder="Insert new password" required min="3"/>
	    </div>
	    <div class="form-group">
	        <label for="inputNewPasswordC">Password Confirmation:</label>
	        <input
	            type="password" class="form-control" v-model="passwordConfirmation"
	            name="newPasswordC" id="inputNewPasswordC"
	            placeholder="Confirm new password" required min="3"/>
	    </div>
	    <div class="form-group">
	        <label for="inputOld">Old Password:</label>
	        <input
	            type="password" class="form-control" v-model="oldPassword"
	            name="old" id="inputOld"
	            placeholder="Insert old password" required min="3"/>
	    </div>

	    <div class="form-group">
	        <a class="btn btn-success" v-on:click.prevent="saveUser()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	module.exports={		
		props: ['user'],

        data: function(){
            return{
                password: "",
                passwordConfirmation: "",
                oldPassword: "",
            }
        },
        
	    methods: {
	        saveUser: function(){	           
				if(this.password == this.passwordConfirmation && this.password != this.oldPassword){
					axios.patch('api/users/password', {
						'password': this.password,
						'password_confirmation': this.passwordConfirmation,
						'old_password': this.oldPassword,
						'userId': this.user.id,
					}) 
	                .then(response=>{	
						console.log(response.data);
						if(response.data == "Old password incorrect"){
							this.$emit('oldpass-error');
						}else{
							this.$store.commit('setUser',response.data.data);
	                		this.$emit('user-saved');
						}	                	
					})
					.catch(error =>{
						console.log(error);
					})
				}else if(this.password == "" || this.passwordConfirmation == "" || this.oldPassword == ""){
					//nova igual antiga
					this.$emit('pass-empty');
				}else if(this.password == this.oldPassword){
					//nova igual antiga
					this.$emit('pass-same');
				}else{
					//erro alteração da password
					this.$emit('pass-canceled');
				}
	        },
	        cancelEdit: function(){	        	
				this.$emit('user-canceled');	                
	        }
		}
	}
</script>
