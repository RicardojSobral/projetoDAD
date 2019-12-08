<template>
	<div class="jumbotron">
	    <h2>Edit Movement: {{ movement.id }}</h2>
	    <div class="form-group">
	        <label for="inputCategory">Category:</label>
	        <input
	            type="text" class="form-control" v-model="movement.category.name"
	            name="category" id="inputCategory" />
	    </div>
	    <div class="form-group">
	        <label for="inputDescription">Description:</label>
	        <input
	            type="text" class="form-control" v-model="movement.description"
	            name="newDescription" id="inputDescription"/>
	    </div>

	    <div class="form-group">
	        <a class="btn btn-success" v-on:click.prevent="saveMovement()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	module.exports={		
		props: ['movement'],
        
	    methods: {
	        saveMovement: function(){	    
                axios.put('api/movements/'+this.movement.id, this.movement)
	                .then(response=>{	 
                        if(response.data == 'Category does not exist for this type of movement'){
                            this.$emit('category-error');
                        }else{
                            this.$emit('save-edit')
                        }
                        //this.$store.commit('setUser', response.data.data);
                    })
                    .catch(error => {
                        console.error(error);
                    })       
			
	        },
	        cancelEdit: function(){	        	
				this.$emit('edit-canceled');	                
	        }
		}
	}
</script>
