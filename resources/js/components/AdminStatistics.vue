<template>
    <div>
        <div v-if="!loadedMovementsMonth" class="jumbotron">
            <h1>Loading Statistics...</h1>
        </div>  

        <div v-if="loadedMovementsMonth">
            <div class="jumbotron">
                <h4>Number Of Active Users: {{ activeUsers }}</h4>
                <br>
                <h4>Number Of Transactions: {{ totalTransactions }}</h4>
                <br>
                <h4>Ammount Of Money In The Platform: {{ platformMoney }}€</h4>
            </div>  

            <br>

            <div v-if="loadedMovementsMonth" class="container">
                <h4>Total Movements Per Month:</h4>
                <line-chart :data="data1" :labels="label1" :color="'#36a2eb'"/>
            </div>      

            <br>

            <div v-if="loadedExternalIncomeMonth" class="container">
                <h4>External Income Per Month:</h4>
                <line-chart :data="data2" :labels="label2" :color="'#36a2eb'"/>
            </div>     

            <br>

            <div v-if="loadedInternalTransfersMonth" class="container">
                <h4>Internal Transfers (Expenses) Per Month:</h4>
                <line-chart :data="data3" :labels="label3" :color="'#36a2eb'"/>
            </div>      

            <br>

            <div v-if="loadedUsersRegisteredMonth" class="container">
                <h4>Users Registered Per Month:</h4>
                <line-chart :data="data4" :labels="label4" :color="'#36a2eb'"/>
            </div>      
        </div>
       

        
    </div>    
</template>

<script type="text/javascript">
    import LineChart from './StatsLineChart.vue';

import { Line } from 'vue-chartjs';

export default {

    data: function(){
        return{
            userId: this.$store.state.user.id,
            activeUsers: null,
            totalTransactions: null,
            platformMoney: null,
            label1:null,
            data1: null,
            label2:null,
            data2: null,
            label3:null,
            data3: null,
            label4:null,
            data4: null,
            loadedMovementsMonth: false,
            loadedExternalIncomeMonth: false,
            loadedInternalTransfersMonth: false,
            loadedUsersRegisteredMonth: false,

        }
    },

    methods: {
        getNumberActiveIUsers: function(){
            axios.get('api/admin/stats/numberActiveUsers')
            .then( response => {
                this.activeUsers = response.data[0];
                this.totalTransactions = response.data[1];
                this.platformMoney = response.data[2];
            })
            .catch(error => {                        
                console.log(error);
            })        
        },

        getMovementsThoughTime: function(){
            axios.get('api/admin/stats/movementsThroughTime')
            .then( response => {
                this.label1 = response.data.labels;
                this.data1 = response.data.rows;
                this.loadedMovementsMonth = true;
            })
           .catch(error => {                        
                console.log(error);
            })
        },

        getExternalIncomeThoughTime: function(){
            axios.get('api/admin/stats/externalIncomeThroughTime')
            .then( response => {
                this.label2 = response.data.labels;
                this.data2 = response.data.rows;
                this.loadedExternalIncomeMonth = true;
            })
           .catch(error => {                        
                console.log(error);
            })
        },

        getInternalTransfersThoughTime: function(){
            axios.get('api/admin/stats/internalTransfersThroughTime')
            .then( response => {
                this.label3 = response.data.labels;
                this.data3 = response.data.rows;
                this.loadedInternalTransfersMonth = true;
            })
           .catch(error => {                        
                console.log(error);
            })
        },

        getUsersRegisteredThroughTime: function(){
            axios.get('api/admin/stats/usersRegisteredThroughTime')
            .then( response => {
                this.label4 = response.data.labels;
                this.data4 = response.data.rows;
                this.loadedUsersRegisteredMonth = true;
            })
           .catch(error => {                        
                console.log(error);
            })
        },
    },

    mounted() {
        this.getNumberActiveIUsers();    
        this.getMovementsThoughTime();   
        this.getExternalIncomeThoughTime();
        this.getInternalTransfersThoughTime();
        this.getUsersRegisteredThroughTime();
    },

    components: {
        'line-chart': LineChart,
    },   
    
}
</script>