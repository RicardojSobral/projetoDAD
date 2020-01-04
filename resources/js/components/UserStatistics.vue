<template>
    <div>
        <div v-if="!loadedIncTime" class="jumbotron">
            <h1>Loading Statistics...</h1>
        </div>  

        <div v-if="loadedIncTime">
            <div v-if="loadedIncExpe" class="container">
                <h4>Total Income/Expenses (€):</h4>
                <income-expense :data="data1" />
            </div>   

            <br>

            <div v-if="loadedExpCat" class="container">
                <h4>Total Expenses By Category (€):</h4>
                <expense-category :data="data2" :labels="labels2"/>
            </div>  

            <br>

            <div v-if="loadedIncCat" class="container">
                <h4>Total Income By Category (€):</h4>
                <incomes-category :data="data3" :labels="labels3" />
            </div>

            <br>

            <div v-if="loadedBalanceTime" class="container">
                <h4>Start Balance Per Month (€):</h4>
                <line-chart :data="rows" :labels="labels" :color="'#33cc33'"/>
            </div>  

            <br>

            <div v-if="loadedExpTime" class="container">
                <h4>Total Expenses Per Month (€):</h4>
                <line-chart :data="data4" :labels="labels4" :color="'#dd4b39'"/>
            </div>  
        
            <br>

            <div v-if="loadedIncTime" class="container">
                <h4>Total Income Per Month (€):</h4>
                <line-chart :data="data5" :labels="labels5" :color="'#36a2eb'"/>
            </div>     
        </div>
     
        
    </div>    
</template>

<script type="text/javascript">
    import TotalsIncomeExpense from './StatsBarsTotals.vue';
    import ExpensesByCategory from './StatsExpensesByCategory.vue';
    import IncomesByCategory from './StatsIncomesByCategory.vue';
    import LineChart from './StatsLineChart.vue';

import { Bar, Line } from 'vue-chartjs';

export default {

    data: function(){
        return{
            userId: this.$store.state.user.id,
            rows: null,
            labels:null,
            data1: null,
            data2: null,
            labels2: null,
            data3: null,
            labels3:null,
            data4: null,
            labels4: null,
            data5: null,
            labels5: null,
            loadedIncExpe: false,
            loadedExpCat: false,
            loadedIncCat: false,
            loadedBalanceTime: false,
            loadedExpTime: false,
            loadedIncTime: false,
        }
    },

    methods: {
        getTotals: function(){
            axios.get('api/users/stats/totals/'+ this.userId)
            .then( response => {
                this.data1 = response.data;
                this.loadedIncExpe = true;
            })            
        },

        getExpensesByCategory: function(){
            axios.get('api/users/stats/expensesByCategory/'+ this.userId)
            .then( response => { 
                this.labels2 = response.data.labels;                 
                this.data2 = response.data.rows;
                this.loadedExpCat = true;
            })            
        },

        getIncomesByCategory: function(){
            axios.get('api/users/stats/incomesByCategory/'+ this.userId)
            .then( response => {     
                this.labels3 = response.data.labels;           
                this.data3 = response.data.rows;
                this.loadedIncCat = true;
            })            
        },

        getBalanceThroughTime: function(){
            axios.get('api/users/stats/balanceThroughTime/'+ this.userId)
            .then( response => {     
                this.labels = response.data.labels;
                this.rows = response.data.rows;
                this.loadedBalanceTime = true;
            })            
        },

        getExpensesThroughTime: function(){
            axios.get('api/users/stats/expensesThroughTime/'+ this.userId)
            .then( response => {     
                this.labels4 = response.data.labels;
                this.data4 = response.data.rows;
                this.loadedExpTime = true;
            })            
        },

        getIncomesThroughTime: function(){
            axios.get('api/users/stats/incomesThroughTime/'+ this.userId)
            .then( response => {     
                this.labels5 = response.data.labels;
                this.data5 = response.data.rows;
                this.loadedIncTime = true;
            })            
        },
    },

    mounted() {
        this.getBalanceThroughTime();
        this.getTotals();
        this.getExpensesByCategory();
        this.getIncomesByCategory();
        this.getExpensesThroughTime();
        this.getIncomesThroughTime();
       
    },

    components: {
        'income-expense': TotalsIncomeExpense,
        'expense-category': ExpensesByCategory,
        'incomes-category': IncomesByCategory,
        'line-chart': LineChart,
    },   
    
}
</script>