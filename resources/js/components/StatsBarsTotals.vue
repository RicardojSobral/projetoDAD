<script>
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,

    props:['data'],

    mounted () {
        this.renderChart({
            labels: ['Total Incomes/Expenses (€)'],
            datasets: [
                {label: 'Total Incomes', backgroundColor: '#36a2eb', data: [this.data[0]]},
                {label: 'Total Expenses', backgroundColor: '#dd4b39', data: [this.data[1]]}
            ]
        }, {responsive: true, maintainAspectRatio: false})
    },
}
</script>

