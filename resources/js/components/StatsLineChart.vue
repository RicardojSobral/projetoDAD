<script>
import { Line } from 'vue-chartjs'

export default {
    extends: Line,

    props:['data', 'labels', 'color'],

    mounted () {
        this.renderChart({
            labels: this.labels,
            datasets: [                
                {                    
                    borderColor: this.color, 
                    pointBackgroundColor: this.color,
                    data: this.data
                },
               
            ]
        }, {responsive: true, maintainAspectRatio: false, legend:{ display: false}})
    },
}
</script>