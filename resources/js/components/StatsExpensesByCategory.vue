<script>
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,

    props:['data', 'labels'],

    mounted () {
        this.renderChart({
            labels: this.labels,
            datasets: [                
                {
                    backgroundColor: '#dd4b39', 
                    data: this.data
                },
                
            ]
        }, {responsive: true, maintainAspectRatio: false, legend:{ display: false}})
    },
}
</script>