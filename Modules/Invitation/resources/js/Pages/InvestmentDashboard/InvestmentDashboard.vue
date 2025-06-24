<template>
  <auth-layout>
  <div class="container-fluid mb-4">
        <div class="row gy-4">
            <div class="col-sm-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="true"
                    :isActionButtons="false" :subTitle="header.subTitle" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-4">
                <div v-for="(item, index) in cardsArray" :key="index">
                    <investment-dashboard-card class="mt-3" :title="item.title" :amount="item.amount"
                        :isFooter="item.isFooter" />
                </div>
            </div>
            <div class="col-lg-8">
                <div class="d-flex gap-3 justify-content-end flex-wrap">
                    <reuseable-button outline round="" textColor="#4682BE" btnHeight="44px"
                        class="px-4">Withdraw</reuseable-button>
                    <reuseable-button @click="handleAddFund" round="" btnHeight="44px" class="px-4">Add
                        Funds</reuseable-button>
                </div>
                <div class="chart">
                    <app-typography variant="body3" fontWeight="500">Progress Bar Chart Investment Vs
                        dividend</app-typography>
                  <div ref="chart" style="width: 100%; height: 400px;"></div>

                </div>
            </div>
            <div class="col-lg-12">
                <common-table :data="tableData" :header="tableColumns" :isActionHeader="true" headerBgColor="#ffff"
                    headerTextColor="#36454F" tableHeading="Transaction History">
                </common-table>
            </div>
            <div class="col-lg-12">
                <permotion-banner />
            </div>
        </div>
    </div>
  </auth-layout>
</template>

<script>
import * as echarts from 'echarts';
import { TitleActionBar, InvestmentDashboardCard, ReuseableButton, AppTypography, PermotionBanner, CommonTable } from "@/components/index";
export default {
    components: {
        InvestmentDashboardCard,
        PermotionBanner,
        ReuseableButton,
        AppTypography,
        TitleActionBar,
        CommonTable
    },
    data() {
        return {
            header: {
                title: "Investment Dashboard (Coming Soon)",
                subTitle: "View-only for now — full features coming soon",
            },
            cardsArray: [
                { title: "Investment Wallet", amount: "1200.00", isFooter: true },
                { title: "Dividend Received", amount: "1200.00", isFooter: false },
                { title: "Dividend Payout", amount: "1200.00", isFooter: false }
            ],
          tableData: [
            {
              investmentID: '12786498',
              accountName: 'Insurance',
              totalInvestment: '$250.0',
              percentageReturn: '20%',
              totalDividend: '2',
              totalWallet: '$50.0'
            },
            {
              investmentID: '12786498',
              accountName: 'Insurance',
              totalInvestment: '$250.0',
              percentageReturn: '20%',
              totalDividend: '2',
              totalWallet: '$50.0'
            },
            {
              investmentID: '12786498',
              accountName: 'Insurance',
              totalInvestment: '$250.0',
              percentageReturn: '20%',
              totalDividend: '2',
              totalWallet: '$50.0'
            },
            {
              investmentID: '12786498',
              accountName: 'Insurance',
              totalInvestment: '$250.0',
              percentageReturn: '20%',
              totalDividend: '2',
              totalWallet: '$50.0'
            }
          ],
          tableColumns: [
            { label: 'Investment ID', field: 'investmentID' },
            { label: 'Account Name', field: 'accountName' },
            { label: 'Total Investment', field: 'totalInvestment' },
            { label: 'Percentage Return PA', field: 'percentageReturn' },
            { label: 'Total Dividend', field: 'totalDividend' },
            { label: 'Total Available in Wallet', field: 'totalWallet' }
          ]
        }
    },
  mounted() {
    this.initChart();
  },
    methods: {
        handleAddFund() {
            this.$router.push("./fund-plan")
        },
      initChart() {
        const chart = echarts.init(this.$refs.chart);

        const option = {
          tooltip: {
            trigger: 'axis',
            axisPointer: {
              type: 'line',
              label: {
                show: false
              }
            }
          },
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: Array.from({ length: 50 }, (_, i) => `Day ${i + 1}`), // You can replace with actual dates
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: { color: '#aaa' }
          },
          yAxis: {
            type: 'value',
            min: 130,
            max: 150,
            axisLine: { show: false },
            axisTick: { show: false },
            splitLine: {
              lineStyle: {
                color: '#333'
              }
            },
            axisLabel: { color: '#aaa' }
          },
          grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            top: '3%',
            containLabel: true
          },
          series: [
            {
              name: 'Price',
              type: 'line',
              smooth: true,
              symbol: 'none',
              lineStyle: {
                color: '#90EE90',
                width: 2
              },
              areaStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                  { offset: 0, color: 'rgba(144,238,144,0.4)' },
                  { offset: 1, color: 'rgba(144,238,144,0.05)' }
                ])
              },
              data: [
                135.2, 136, 137, 138, 137.5, 139, 140.5, 139.8, 141.2, 143.0, 144.5, 146.0, 147.3, 147.8, 146.5, 144.9,
                143.5, 142.0, 141.5, 139.2, 138.5, 137.3, 136.0, 134.9, 135.5, 136.2, 137.0, 138.5, 139.7, 141.3,
                142.8, 144.4, 145.8, 147.5, 149.0, 147.6, 145.3, 143.8, 142.5, 141.7, 140.6, 139.4, 138.0, 137.3, 136.5, 135.9, 135.2, 134.7, 137.3
              ]
            }
          ]
        };

        chart.setOption(option);
      }




    }
}
</script>

<style scoped></style>