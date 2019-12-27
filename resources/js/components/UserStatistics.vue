<template>
    <div>
        <div v-if="loadedIncExpe" class="container">
            <h4>Total Income/Expenses (€):</h4>
            <income-expense :data="data1" />
        </div>   

        <br>

        <div v-if="loadedExpCat" class="container">
            <h4>Total Expenses By Category (€):</h4>
            <expense-category :data="data2" />
        </div>  

        <br>

        <div v-if="loadedIncCat" class="container">
            <h4>Total Income By Category (€):</h4>
            <incomes-category :data="data3" />
        </div>

        <br>

        <div v-if="loadedBalanceTime" class="container">
            <h4>Start Balance Per Month (€):</h4>
            <balance-time :data="rows" :labels="labels" />
        </div>  
        
    </div>    
</template>

<script type="text/javascript">
    import TotalsIncomeExpense from './StatsBarsTotals.vue';
    import ExpensesByCategory from './StatsExpensesByCategory.vue';
    import IncomesByCategory from './StatsIncomesByCategory.vue';
    import BalanceTime from './StatsBalanceTime.vue';

import { Bar, Line } from 'vue-chartjs';

export default {

    data: function(){
        return{
            userId: this.$store.state.user.id,
            data1: null,
            data2: null,
            data3: null,
            rows: null,
            labels:null,
            loadedIncExpe: false,
            loadedExpCat: false,
            loadedIncCat: false,
            loadedBalanceTime: false,

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
                this.data2 = response.data;
                this.loadedExpCat = true;
            })            
        },

        getIncomesByCategory: function(){
            axios.get('api/users/stats/incomesByCategory/'+ this.userId)
            .then( response => {                
                this.data3 = response.data;
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
    },

    async mounted() {
        await this.getTotals();
        await this.getExpensesByCategory();
        await this.getIncomesByCategory();
        await this.getBalanceThroughTime();
    },

    components: {
        'income-expense': TotalsIncomeExpense,
        'expense-category': ExpensesByCategory,
        'incomes-category': IncomesByCategory,
        'balance-time': BalanceTime,
    },   
    
}
</script>