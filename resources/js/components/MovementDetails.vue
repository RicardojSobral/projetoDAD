<template>
     <div class="jumbotron">
        <h2>Movement {{ movement.id }}</h2>

        <br>

        <div class="form-group" v-if="movement.transfer_wallet">
            <div class="col-md-10 col-md-offset-1" v-if="movement.transfer_wallet.user.photo">            
                <td><img v-bind:src="'storage/fotos/' + movement.transfer_wallet.user.photo" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
            </div>
            <div class="col-md-10 col-md-offset-1" v-if="!movement.transfer_wallet.user.photo">            
                <td><img v-bind:src="'storage/fotos/unknown.jpg'" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
            </div>
        </div>

        <div class="form-group">
	        <label v-if="movement.description">Description:   {{ movement.description}}</label>
	        <label v-if="!movement.description">Description:   Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="movement.source_description">Source Description:   {{ movement.source_description}}</label>
	        <label v-if="!movement.source_description">Source Description:   Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="movement.iban">IBAN:   {{ movement.iban}}</label>
	        <label v-if="!movement.iban">IBAN:   Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="movement.mb_entity_code">MB Entity Code:   {{ movement.mb_entity_code}}</label>
	        <label v-if="!movement.mb_entity_code">MB Entity Code:   Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="movement.mb_payment_reference">MB Payment Reference:   {{ movement.mb_payment_reference}}</label>
	        <label v-if="!movement.mb_payment_reference">MB Payment Reference:   Empty</label>
	    </div>
       
       <button type="button" class="btn btn-dark" v-on:click.prevent="cancelDetails()">Close</button>
    </div>
</template>

<script>
    export default {
        props: ['movement'],

        methods: {
            cancelDetails: function(){
                this.$emit('details-canceled'); 
            },
           
        },
    }
</script>
