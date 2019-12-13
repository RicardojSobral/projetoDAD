<template>
    <div class="jumbotron">
        <h2>Register Credit</h2>

        <div class="alert alert-danger" v-if="showError">			 
			<button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

	    <div class="form-group">
	        <label for="inputEmail">Email To Credit:</label>
	        <input
	            type="email" class="form-control" v-model="email"
	            name="email" id="inputEmail" 
	            placeholder="Insert email of the account to receive the money" required
                title="Email must be a valid user email"/>
	    </div>

        <div class="form-group">
	        <label for="inputValue">Value To Credit:</label>
	        <input
	            type="text" class="form-control" v-model="value"
	            name="value" id="inputValue" 
	            placeholder="Insert value to credit" required
                title="Value needs to be between 0.1 and 5000"/>
	    </div>

        <div class="form-group">
	       <label for="type_payment">Type Of Payment:</label>
            <select name="type_payment" id="type_payment" class="form-control" v-model="type_payment" required>
                <option disabled selected> -- select an option -- </option>
                <option value="c">Cash</option>
                <option value="bt">Bank Transfer</option>
                <option value="mb">MB Payment</option>
            </select>
	    </div>

        <div v-if="this.type_payment == 'bt'" >
            <div class="form-group">
                <label for="inputIBAN">IBAN:</label>
                <input
                    type="text" class="form-control" v-model="iban"
                    name="iban" id="inputIBAN" 
                    placeholder="Insert IBAN" required
                    title="INAN must be 2 upper letters followed by 23 numbers"/>
	        </div>

            <div class="form-group">
                <label for="inputSourceDescription">Source Description:</label>
                <input
                    type="text" class="form-control" v-model="source_description"
                    name="source_description" id="inputSourceDescription" 
                    placeholder="Insert a source description" required/>
            </div>
        </div>

        <div class="form-group">
	        <a class="btn btn-success" v-on:click.prevent="createCredit()">Create Credit</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelCredit()">Cancel</a>
	    </div>

    </div>    
</template>

<script>
    export default {
        data: function(){
            return{
                email: "",
                value: "",
                type_payment: "",
                iban: null,
                source_description: null,
                showError: false,
                successMessage: "",
            }
        },

        methods: {
            createCredit: function(){
                axios.post('api/movements/credit', {
                        email: this.email,
                        value: this.value,
                        type_payment: this.type_payment,
                        iban: this.iban,
                        source_description: this.source_description,
                    })
                    .then(response => {
                        if(response.data == "Email is not valid!"){
							this.$emit('email-error');
						}else{
                            this.$socket.emit("user_changed_income", this.value, response.data);
                            this.$emit('credit-created');
                        }
                    })                    
                    .catch(error => {      
                        console.error(error);
                        if (error.response.data.errors){
                            if(error.response.data.errors.email){
                            this.successMessage = error.response.data.errors.email[0];
                            this.showError = true;
                            }else if (error.response.data.errors.value){
                                this.successMessage = error.response.data.errors.value[0];
                                this.showError = true;
                            }else if (error.response.data.errors.type_payment){
                                this.successMessage = error.response.data.errors.type_payment[0];
                                this.showError = true;
                            }else if (error.response.data.errors.iban){
                                this.successMessage = error.response.data.errors.iban[0];
                                this.showError = true;
                            }else if (error.response.data.errors.source_description){
                                this.successMessage = error.response.data.errors.source_description[0];
                                this.showError = true;
                            }
                        }else {
                            this.successMessage = 'Value must be a numeric value between 0.1 and 5000';
                            this.showError = true;
                        }
                    })
            },

            cancelCredit: function(){
                this.$emit('credit-canceled');
            },
        }
       
    }
</script>
<style scoped>

</style>