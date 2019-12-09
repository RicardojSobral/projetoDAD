<template>
    <div class="jumbotron">
        <h2>Register Debit</h2>

        <div class="alert alert-danger" v-if="showError">
            <button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="form-group">
            <label for="inputEmail">Email To Debit:</label>
            <input
                type="email" class="form-control" v-model="email"
                name="email" id="inputEmail"
                placeholder="Insert email of the account to receive the money" required
                title="Email must be a valid user email"/>
        </div>

        <div class="form-group">
            <label for="inputValue">Value To Debit:</label>
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
        name: "RegisterDebit"
    }
</script>

<style scoped>

</style>
