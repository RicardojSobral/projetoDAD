<template> 
    <div class="jumbotron">
        <h2>Create Admin/Operator</h2>
        <div class="accountCreate-form">
            <div class="form-group">
                <label for="inputName">Name: </label>
                <input type="text" class="form-control" id="inputName" v-model="user.name" placeholder="Name" required>
            </div>

            <div class="form-group">
                <label for="inputEmail">Email: </label>
                <input type="text" class="form-control" id="inputEmail" v-model="user.email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="inputPassword">Password: </label>
                <input type="password" class="form-control" id="inputPassword" v-model="user.password" placeholder="Password" required>
            </div>

            <label>Photo: </label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="inputPhoto" v-on:change="onImageChange" ref="fileInput" required>
                <label class="custom-file-label" for="inputPhoto">{{ user.photo }}</label>
            </div>
            
            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="createUserAdmin()">Save</a>
                <a class="btn btn-light" v-on:click.prevent="cancelCreate()">Cancel</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
               user: {
                    name: '',
                    nif: '',
                    type: '',
                    photo: '',
                    photoBase64: '',
                },
            }
        },
        methods: {
            createUserAdmin: function() {
                
            },
            cancelCreate: function(){
                this.$emit('create-canceled');
            },

            onImageChange: function(event){     //UPLOAD IMAGE METHODS
                let image = event.target.files[0];
                this.user.photo = image.name;
                this.createImage(image);
            },
            createImage: function(file){
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.user.photoBase64 = e.target.result;
                };
                reader.readAsDataURL(file);
            }         

        }
    }
</script>

<style scoped>
</style>