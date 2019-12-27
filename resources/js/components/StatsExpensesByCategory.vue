<script>
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,

    props:['data'],

    mounted () {
        this.renderChart({
            labels: ['Total Expenses By Category (€)'],
            datasets: [                
                {label: this.data[0]['category'], backgroundColor: '#dd4b39', data: [this.data[0]['ammount']]},
                {label: this.data[1]['category'], backgroundColor: '#dd4b39', data: [this.data[1]['ammount']]},
                {label: this.data[2]['category'], backgroundColor: '#dd4b39', data: [this.data[2]['ammount']]},
                {label: this.data[3]['category'], backgroundColor: '#dd4b39', data: [this.data[3]['ammount']]},
                {label: this.data[4]['category'], backgroundColor: '#dd4b39', data: [this.data[4]['ammount']]},
                {label: this.data[5]['category'], backgroundColor: '#dd4b39', data: [this.data[5]['ammount']]},
                {label: this.data[6]['category'], backgroundColor: '#dd4b39', data: [this.data[6]['ammount']]},
                {label: this.data[7]['category'], backgroundColor: '#dd4b39', data: [this.data[7]['ammount']]},
                {label: this.data[8]['category'], backgroundColor: '#dd4b39', data: [this.data[8]['ammount']]},
                {label: this.data[9]['category'], backgroundColor: '#dd4b39', data: [this.data[9]['ammount']]},
                {label: this.data[10]['category'], backgroundColor: '#dd4b39', data: [this.data[10]['ammount']]},
                {label: this.data[11]['category'], backgroundColor: '#dd4b39', data: [this.data[11]['ammount']]},
                {label: this.data[12]['category'], backgroundColor: '#dd4b39', data: [this.data[12]['ammount']]},
                {label: this.data[13]['category'], backgroundColor: '#dd4b39', data: [this.data[13]['ammount']]},
                {label: this.data[14]['category'], backgroundColor: '#dd4b39', data: [this.data[14]['ammount']]},
                {label: this.data[15]['category'], backgroundColor: '#dd4b39', data: [this.data[15]['ammount']]},
                {label: this.data[16]['category'], backgroundColor: '#dd4b39', data: [this.data[16]['ammount']]},
                {label: this.data[17]['category'], backgroundColor: '#dd4b39', data: [this.data[17]['ammount']]},
                {label: this.data[18]['category'], backgroundColor: '#dd4b39', data: [this.data[18]['ammount']]},
            ]
        }, {responsive: true, maintainAspectRatio: false, legend:{ display: false}})
    },
}
</script>