<template>
  <div class="payment-analytic-wrapper">
    <div class="card">
      <div class="d-flex justify-content-between flex-wrap">
        <app-typography variant="body3" fontWeight="600" color="#4682BE">
          {{ cardTitle }}
        </app-typography>
        <p></p>
      </div>
      <div ref="chart" class="chart"></div>
    </div>
  </div>
</template>

<script>
import * as echarts from 'echarts';
import AppTypography from "../Typography/appTypography.vue";

export default {
  components: {
    AppTypography
  },
  props: {
    cardTitle: {
      type: String,
      default: 'Payment Analytics'
    }
  },
  mounted() {
    this.initChart();
  },
  methods: {
    initChart() {
      const chartDom = this.$refs.chart;
      const myChart = echarts.init(chartDom);
      const option = {
        tooltip: {
          trigger: 'item'
        },
        legend: {
          bottom: '0%',
          left: 'center'
        },
        series: [
          {
            name: 'Access From',
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: true,
            label: {
              show: false,
              position: 'center'
            },
            emphasis: {
              label: {
                show: true,
                fontSize: 24,
                fontWeight: 'bold'
              }
            },
            labelLine: {
              show: false
            },
            data: [
              { value: 1048, name: 'Payment' },
              { value: 735, name: 'Receive' },
              { value: 580, name: 'Send' },
              { value: 484, name: 'Deposit' },
              { value: 300, name: 'Withdraw' }
            ]
          }
        ]
      };
      myChart.setOption(option);
    }
  }
};
</script>

<style scoped>
.card {
  box-shadow: 0px 0px 4px 0px #00000026;
  padding: 24px 12px 33px 12px;
  border-radius: 10px;
  height: 100%;
}

.chart {
  width: 100%;
  height: 350px;
}
</style>
