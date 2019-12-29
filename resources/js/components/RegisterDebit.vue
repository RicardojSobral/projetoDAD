<template>
    <div class="jumbotron">
        <h2>Register Debit</h2>

        <div class="alert alert-danger" v-if="showError">
            <button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert" :class="typeofmsg" v-if="showMessage">
            <strong>{{ message }}</strong>
        </div>

        <div class="form-group">
            <label for="inputType_movement">Type Of Movement:</label>
            <select name="type_movement" id="inputType_movement" class="form-control" v-model="movement.type_movement" required>
                <option selected value=""> -- select an option -- </option>
                <option value="0">Payment</option>
                <option value="1">Transfer</option>
            </select>
            <div class="invalid-feedback">
                {{ errors.type_movement }}
            </div>
        </div>

        <div class="form-group">
            <label for="inputValue">Value:</label>
            <input type="number" class="form-control" v-model="movement.value" name="value" id="inputValue"
                placeholder="Insert the amount to send" required
                title="You must enter a valid value"/>
            <div class="invalid-feedback">
                {{ errors.value }}
            </div>
        </div>

        <div class="form-group">
            <label for="inputCategory">Category of expense:</label>
            <select name="category" id="inputCategory" class="form-control" v-model="movement.category" required>
                <option selected value=""> -- select an option -- </option>
                <option v-for="category in movement.categories" v-bind:value="category.id">
                    {{ category.name }}
                </option>
            </select>
            <div class="invalid-feedback">
                {{ errors.category_id }}
            </div>
        </div>

        <div class="form-group">
            <label for="inputDescription">Description:</label>
            <input
                type="text" class="form-control" v-model="movement.description"
                name="description" id="inputDescription"
                placeholder="Insert a description of the movement"/>
            <div class="invalid-feedback">
                {{ errors.description }}
            </div>
        </div>

        <div v-if="movement.type_movement === '0'">
            <div class="form-group">
                <label for="inputType_payment">Type Of Payment:</label>
                <select name="type_payment" id="inputType_payment" class="form-control" v-model="movement.type_payment" required>
                    <option selected value=""> -- select an option -- </option>
                    <option value="bt">Bank Transfer</option>
                    <option value="mb">MB Payment</option>
                </select>
                <div class="invalid-feedback">
                    {{ errors.type_payment }}
                </div>
            </div>

            <div v-if="movement.type_payment === 'bt'" >
                <div class="form-group">
                    <label for="inputIBAN">IBAN:</label>
                    <input
                        type="text" class="form-control" v-model="movement.iban"
                        name="iban" id="inputIBAN"
                        placeholder="Insert IBAN" required
                        title="INAN must be 2 upper letters followed by 23 numbers"/>
                    <div class="invalid-feedback">
                        {{ errors.iban }}
                    </div>
                </div>
            </div>

            <div v-if="movement.type_payment === 'mb'">
                <div class="form-group">
                    <label for="inputMBEntity">MB Entity Code:</label>
                    <input
                        type="text" class="form-control" v-model="movement.MBEntity"
                        name="mbentity" id="inputMBEntity"
                        placeholder="Insert MB Entity Code" required
                        title="Entity code must have 5 numbers"/>
                    <div class="invalid-feedback">
                        {{ errors.mb_entity_code }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMBReference">MB Payment Reference:</label>
                    <input
                        type="text" class="form-control" v-model="movement.MBReference"
                        name="mbreference" id="inputMBReference"
                        placeholder="Insert MB Payment Reference" required
                        title="Payment Reference must have 9 numbers"/>
                    <div class="invalid-feedback">
                        {{ errors.mb_payment_reference }}
                    </div>
                </div>
            </div>
        </div>

        <div v-if="movement.type_movement === '1'">
            <div class="form-group">
                <label for="inputEmail">Email of the destination wallet:</label>
                <input
                    type="email" class="form-control" v-model="movement.email"
                    name="email" id="inputEmail"
                    placeholder="Insert email of the destination wallet" required
                    title="Email must be a valid user email"/>
                <div class="invalid-feedback">
                    {{ errors.email }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputSourceDescription">Source Description:</label>
                <input
                    type="text" class="form-control" v-model="movement.source_description"
                    name="source_description" id="inputSourceDescription"
                    placeholder="Insert a source description" required/>
                <div class="invalid-feedback">
                    {{ errors.source_description }}
                </div>
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
        data: function() {
            return {
                sourceUser: this.$store.state.user.name,
                movement: {
                    type_movement: '',
                    value: '',
                    category: '',
                    description: '',
                    type_payment: '',
                    iban: '',
                    MBEntity: '',
                    MBReference:'',
                    email: '',
                    source_description: '',
                    categories: '',
                },
                errors: {
                    type_movement: '',
                    value: '',
                    category_id: '',
                    description: '',
                    type_payment: '',
                    iban: '',
                    mb_entity_code: '',
                    mb_payment_reference: '',
                    email: '',
                    source_description: '',
                },
                typeofmsg: "alert-success",
                showMessage: false,
                message: "",
                showError: false,
            }
        },

        methods: {
            createDebit: function() {
                axios.post('api/movements/debit', {
                    transfer: this.movement.type_movement,
                    type_payment: this.movement.type_payment,
                    category_id: this.movement.category,
                    iban: this.movement.iban,
                    mb_entity_code: this.movement.MBEntity,
                    mb_payment_reference: this.movement.MBReference,
                    description: this.movement.description,
                    source_description: this.movement.source_description,
                    email: this.movement.email,
                    value: this.movement.value
                })
                    .then(response => {
                        if(response.data == "Email is not valid!"){
                            this.$emit('email-error');
                        }else{
                            if(this.movement.type_movement == "1"){
                                this.$socket.emit("user_changed_transfer", this.movement.value, this.sourceUser, response.data);
                            }
                            this.$emit('debit-created');
                        }
                    })
                    .catch(error => {
                        if (error.response.data.errors){
                            let formError = error.response.data.errors;

                            if (formError.type_movement) {
                                this.errors.type_movement = formError.type_movement[0];
                                document.querySelector('#inputType_movement').classList.add('is-invalid');
                            } else {
                                document.querySelector('#inputType_movement').classList.remove('is-invalid');
                            }

                            if (formError.value) {
                                this.errors.value = formError.value[0];
                                document.querySelector('#inputValue').classList.add('is-invalid');
                            } else {
                                document.querySelector('#inputValue').classList.remove('is-invalid');
                            }

                            if (formError.category_id) {
                                this.errors.category_id = formError.category_id[0];
                                document.querySelector('#inputCategory').classList.add('is-invalid');
                            } else {
                                document.querySelector('#inputCategory').classList.remove('is-invalid');
                            }

                            if (formError.description) {
                                this.errors.description = formError.description[0];
                                document.querySelector('#inputDescription').classList.add('is-invalid');
                            } else {
                                document.querySelector('#inputDescription').classList.remove('is-invalid');
                            }

                            if (this.movement.type_movement === '0') {
                                if (formError.type_payment) {
                                    this.errors.type_payment = formError.type_payment[0];
                                    document.querySelector('#inputType_payment').classList.add('is-invalid');
                                } else {
                                    document.querySelector('#inputType_payment').classList.remove('is-invalid');
                                }

                                if (this.movement.type_payment === 'bt') {
                                    if (formError.iban) {
                                        this.errors.iban = formError.iban[0];
                                        document.querySelector('#inputIBAN').classList.add('is-invalid');
                                    } else {
                                        document.querySelector('#inputIBAN').classList.remove('is-invalid');
                                    }
                                } else if (this.movement.type_payment === 'mb') {
                                    if (formError.mb_entity_code) {
                                        this.errors.mb_entity_code = formError.mb_entity_code[0];
                                        document.querySelector('#inputMBEntity').classList.add('is-invalid');
                                    } else {
                                        document.querySelector('#inputMBEntity').classList.remove('is-invalid');
                                    }

                                    if (formError.mb_payment_reference) {
                                        this.errors.mb_payment_reference = formError.mb_payment_reference[0];
                                        document.querySelector('#inputMBReference').classList.add('is-invalid');
                                    } else {
                                        document.querySelector('#inputMBReference').classList.remove('is-invalid');
                                    }
                                }
                            }

                            if (this.movement.type_movement === '1') {
                                if (formError.email) {
                                    this.errors.email = formError.email[0];
                                    document.querySelector('#inputEmail').classList.add('is-invalid');
                                } else {
                                    document.querySelector('#inputEmail').classList.remove('is-invalid');
                                }

                                if (formError.source_description) {
                                    this.errors.source_description = formError.source_description[0];
                                    document.querySelector('#inputSourceDescription').classList.add('is-invalid');
                                } else {
                                    document.querySelector('#inputSourceDescription').classList.remove('is-invalid');
                                }
                            }
                        }
                    })
            },

            getCategories: function() {
                axios.get('api/categories/e').then(response => {
                    this.movement.categories = response.data
                })
            },

            cancelDebit: function(){
                this.$emit('debit-canceled');
            },
        },

        mounted() {
            this.getCategories();
        }

    }
</script>

<style scoped>

</style>
