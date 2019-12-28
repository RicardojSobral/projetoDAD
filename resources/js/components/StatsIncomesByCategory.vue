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
                    backgroundColor: '#36a2eb',
                    data: this.data,
                },
            ]
        }, {responsive: true, maintainAspectRatio: false, legend:{ display: false}})
    },
}
</script>