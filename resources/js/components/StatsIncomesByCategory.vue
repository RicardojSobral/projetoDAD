<script>
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,

    props:['data'],

    mounted () {
        this.renderChart({
            labels: ['Total Incomes By Category (€)'],
            datasets: [                
                {label: this.data[0]['category'], backgroundColor: '#36a2eb', data: [this.data[0]['ammount']]},
                {label: this.data[1]['category'], backgroundColor: '#36a2eb', data: [this.data[1]['ammount']]},
                {label: this.data[2]['category'], backgroundColor: '#36a2eb', data: [this.data[2]['ammount']]},
                {label: this.data[3]['category'], backgroundColor: '#36a2eb', data: [this.data[3]['ammount']]},
                {label: this.data[4]['category'], backgroundColor: '#36a2eb', data: [this.data[4]['ammount']]},
                {label: this.data[5]['category'], backgroundColor: '#36a2eb', data: [this.data[5]['ammount']]},
                {label: this.data[6]['category'], backgroundColor: '#36a2eb', data: [this.data[6]['ammount']]},
                {label: this.data[7]['category'], backgroundColor: '#36a2eb', data: [this.data[7]['ammount']]},
                {label: this.data[8]['category'], backgroundColor: '#36a2eb', data: [this.data[8]['ammount']]},
                {label: this.data[9]['category'], backgroundColor: '#36a2eb', data: [this.data[9]['ammount']]},
            ]
        }, {responsive: true, maintainAspectRatio: false, legend:{ display: false}})
    },
}
</script>