<template>
    <div>
        <div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

        <div class="col-md-10 col-md-offset-1">            
            <td><img v-bind:src="'storage/fotos/' + getActualPhoto()" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
        </div>

        <div class="form-group">
            <input
                type="file" class="form-control"
                name="photo" id="inputPhoto" @change="onPhotoSelected"
                />           
        </div>

        <div class="form-group">
	        <label for="inputName">Name:</label>
	        <input
	            type="text" class="form-control" v-model="user.name"
	            name="name" id="inputName" required 
                pattern="^[A-ZÀ-úa-z\s]+$"
                title="Name can only have letters and spaces">
	    </div>

        <div class="form-group" v-if="user.type == 'u'" >
	        <label for="inputNIF">NIF:</label>
	        <input
	            type="integer" class="form-control" v-model="user.nif"
	            name="nif" id="inputNIF" pattern="^[0-9]+$" 
                title="NIF needs to have 9 numbers"
                max="9" min="9" required/>
	    </div>

        <div>
            <button type="submit" class="btn btn-success" v-on:click.prevent="save(user)">Save</button>
            <button type="button" class="btn btn-default" v-on:click.prevent="cancel()">Reset</button>
        </div>

        <br>

        <div>
            <button type="button" class="btn btn-primary" v-on:click.prevent="editPassword(user)">Edit Password</button>
        </div>

        <br>

        <div class="alert alert-danger" v-if="showError">			 
			<button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

        <user-edit :user="user" @user-saved="savedUser" @user-canceled="cancelEdit" @pass-canceled="passCanceled"
        @pass-same="passSame" @pass-empty="passEmpty" @oldpass-error="oldPassError" v-if="showPassword"></user-edit>	

    </div>
</template>

<script type="text/javascript">
    import UserEdit from './UserPasswordEdit.vue';

    export default {
        data: function(){
            return{
                user: this.$store.state.user,
                showPassword: null,
                showSuccess: false,
                showError: false,
                successMessage: "",
                actualPhoto: "",
                photo_file: "",
            }
        },
        methods:{                
        
            save: function(){
	            axios.put('api/users/'+this.user.id, this.user)
	                .then(response=>{	 
                        //this.$store.commit('setUser',response.data.data);               	
                        this.showSuccess = true;
	                    this.successMessage = 'User Saved';
                    })
                    .catch(error => {
                        console.error(error);
                        if(error.response.data.errors.name){
                            this.successMessage = error.response.data.errors.name[0];
                            this.showError = true;
                        }else if(error.response.data.errors.nif){
                            this.successMessage = error.response.data.errors.nif[0];
                            this.showError = true;
                        }else if (error.response.data.errors.photo){
                            this.successMessage = error.response.data.errors.photo[0];
                            this.showError = true;
                        }
                    })
            },
            cancel: function(){
                axios.get('api/users/cancel/'+this.user.id)
	                .then(response=>{
	                	Object.assign(this.user, response.data.data);
                    })
            },
            editPassword: function(){
                this.showPassword = true;
            },
            savedUser: function(){
	            this.showPassword = false;
	            this.showSuccess = true;
	            this.successMessage = 'New Password Saved';
	        },
	        cancelEdit: function(){
	            this.showPassword = false;
            },
            passCanceled: function(){
	            this.showError = true;
	            this.successMessage = 'Invalid password credentials';
            },
            passSame: function(){
	            this.showError = true;
	            this.successMessage = 'New password cannot be the same as old!';
            },
            passEmpty: function(){
	            this.showError = true;
	            this.successMessage = 'All fields are required!';
            },
            oldPassError: function(){
	            this.showError = true;
	            this.successMessage = 'Old password incorrect!';
            },
            getActualPhoto: function(){                
                return this.actualPhoto;
            },
            onPhotoSelected: function(event){
                this.user.photo = event.target.files[0].name;
                this.photo_file = event.target.files[0];    //nao funciona...
            }
        },

         mounted() {
            this.actualPhoto = this.user.photo;
        },

        components: {
	    	'user-edit': UserEdit
	    },       
    }
</script>
<style scoped>

</style>