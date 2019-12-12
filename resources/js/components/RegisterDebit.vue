<template>
    <div class="jumbotron">
        <h2>Register Debit</h2>

        <div class="alert alert-danger" v-if="showError">
            <button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="form-group">
            <label for="type_movement">Type Of Movement:</label>
            <select name="type_movement" id="type_movement" class="form-control" v-model="type_movement" required>
                <option disabled selected> -- select an option -- </option>
                <option value="payment">Payment</option>
                <option value="transfer">Transfer</option>
            </select>
        </div>

        <div class="form-group">
            <label for="inputValue">Value:</label>
            <input
                type="number" class="form-control" v-model="value"
                name="value" id="inputValue"
                placeholder="Insert email of the account to send the money" required
                title="You must enter a valid value"/>
        </div>

        <div class="form-group">
            <label for="inputCategory">Category of expense:</label>
            <input
                type="email" class="form-control" v-model="category"
                name="email" id="inputCategory"
                placeholder="Insert email of the account to send the money" required
                title="Email must be a valid user email"/>
        </div>

        <div class="form-group">
            <label for="inputDescription">Description:</label>
            <input
                type="text" class="form-control" v-model="description"
                name="description" id="inputDescription"
                placeholder="Insert a description of the movement"/>
        </div>

        <div v-if="type_movement === 'payment'">
            <div class="form-group">
                <label for="type_payment">Type Of Payment:</label>
                <select name="type_payment" id="type_payment" class="form-control" v-model="type_payment" required>
                    <option disabled selected> -- select an option -- </option>
                    <option value="bt">Bank Transfer</option>
                    <option value="mb">MB Payment</option>
                </select>
            </div>

            <div v-if="type_payment === 'bt'" >
                <div class="form-group">
                    <label for="inputIBAN">IBAN:</label>
                    <input
                        type="text" class="form-control" v-model="iban"
                        name="iban" id="inputIBAN"
                        placeholder="Insert IBAN" required
                        title="INAN must be 2 upper letters followed by 23 numbers"/>
                </div>
            </div>

            <div v-if="type_payment === 'mb'">
                <div class="form-group">
                    <label for="inputMBEntity">MB Entity Code:</label>
                    <input
                        type="text" class="form-control" v-model="MBEntity"
                        name="mbentity" id="inputMBEntity"
                        placeholder="Insert MB Entity Code" required
                        title="Entity code must have 5 numbers"/>
                </div>
                <div class="form-group">
                    <label for="inputMBReference">MB Payment Reference:</label>
                    <input
                        type="text" class="form-control" v-model="MBReference"
                        name="mbreference" id="inputMBReference"
                        placeholder="Insert MB Payment Reference" required
                        title="Payment Reference must have 9 numbers"/>
                </div>
            </div>
        </div>

        <div v-if="type_movement === 'transfer'">
            <div class="form-group">
                <label for="inputEmail">Email of the destination wallet:</label>
                <input
                    type="email" class="form-control" v-model="email"
                    name="email" id="inputEmail"
                    placeholder="Insert email of the wallet to send the money" required
                    title="Email must be a valid user email"/>
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
            <a class="btn btn-success" v-on:click.prevent="createDebit()">Register Movement</a>
            <a class="btn btn-light" v-on:click.prevent="cancelDebit()">Cancel</a>
        </div>

    </div>
</template>

<script>
    export default {
        data: function(){
            return{
                type_movement: '',
                value: '',
                category: '',
                description: '',
                type_payment: '',
                iban: '',
                MBEntity: '',
                MBReference:'',
                email: '',
                source_description: ''
            }
        },

        methods: {
            createDebit: function(){
                axios.post('api/movements/debit', this)
                    .then(response => {
                        if(response.data == "Email is not valid!"){
                            this.$emit('email-error');
                        }else{
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

            cancelDebit: function(){
                this.$emit('credit-canceled');
            },
        }

    }
</script>

<style scoped>

</style>
