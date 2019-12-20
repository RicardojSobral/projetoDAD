<template>
    <div>
        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert alert-danger" v-if="showError">
            <button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="accountCreate-form">
            <div class="form-group">
                <label for="inputName">Name: </label>
                <input type="text" class="form-control" id="inputName" v-model="user.name" placeholder="Name" required>
                <div class="invalid-feedback" v-if="invalidName">
                    Please provide a valid username.
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email: </label>
                <input type="text" class="form-control" id="inputEmail" v-model="user.email" placeholder="Email" required>
                <div class="invalid-feedback" v-if="invalidEmail">
                    Please provide a valid email.
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password: </label>
                <input type="password" class="form-control" id="inputPassword" v-model="user.password" placeholder="Password" required>
                <div class="invalid-feedback" v-if="invalidPassword">
                    Please provide a valid password.
                </div>
            </div>
            <div class="form-group">
                <label for="inputNif">NIF: </label>
                <input type="text" class="form-control" id="inputNif" v-model="user.nif" placeholder="NIF" required>
                <div class="invalid-feedback" v-if="invalidNif">
                    Please provide a valid nif.
                </div>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="inputPhoto" v-on:change="onImageChange" ref="fileInput">
                <label class="custom-file-label" for="inputPhoto">{{ user.photo.name }}</label>
                <div class="invalid-feedback" v-if="invalidPhoto">
                    Please provide a valid photo.
                </div>
            </div>

            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="createUser()">Save</a>
                <!--<a class="btn btn-light" v-on:click.prevent="cancelForm()">Cancel</a>-->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                showSuccess: false,
                showError: false,
                successMessage: '',
                user: {
                    name: '',
                    email: '',
                    password: '',
                    nif: '',
                    photo: {
                        name: 'Choose a Photo',
                        base64: '',
                    },
                },
                invalidName: false,
                invalidEmail: false,
                invalidPassword: false,
                invalidNif: false,
                invalidPhoto: false
            }
        },
        methods: {
            onImageChange(e) {
                let image = e.target.files[0];
                this.user.photo.name = image.name;
                this.createImage(image);
            },
            createImage(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.user.photo.base64 = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            createUser() {
                axios.post('api/users/create', this.user)
                    .then(response => {
                        this.showSuccess = true;
                        this.successMessage = 'User Created';
                        this.currentUser = null;
                        this.$router.push('/login')
                    })
                    .catch(error => {
                        console.error(error)
                         if(error.response.data.errors.name){
                            this.successMessage = error.response.data.errors.name[0];
                            this.showError = true;
                        }else if(error.response.data.errors.email){
                            this.successMessage = error.response.data.errors.email[0];
                            this.showError = true;
                        }else if(error.response.data.errors.password){
                            this.successMessage = error.response.data.errors.password[0];
                            this.showError = true;
                        }else if(error.response.data.errors.nif){
                            this.successMessage = error.response.data.errors.nif[0];
                            this.showError = true;
                        }else if (error.response.data.errors.photo){
                            this.successMessage = error.response.data.errors.photo[0];
                            this.showError = true;
                        }
                    })
            }
        }
    }
</script>

<style scoped>
</style>
