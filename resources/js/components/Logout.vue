<template>
    
</template>

<script>
export default {
    created(){
        axios.post('api/logout')
          .then(response => {
              this.$store.commit('clearUserAndToken');
          })
          .catch(error => {
              this.$store.commit('clearUserAndToken');
              console.log(error);
          })
        this.$router.push('/')
    }
}
</script>