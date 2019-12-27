<script>
import { Line } from 'vue-chartjs'

export default {
    extends: Line,

    props:['data', 'labels'],

    mounted () {
        this.renderChart({
            labels: this.labels,
            datasets: [                
                {                    
                    borderColor: '#33cc33', 
                    pointBackgroundColor:'#33cc33',
                    data: this.data
                },
               
            ]
        }, {responsive: true, maintainAspectRatio: false, legend:{ display: false}})
    },
}
</script>