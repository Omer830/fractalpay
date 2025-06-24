<template>
  <auth-layout>

    <div class="container-fluid mb-4">
      <div v-if="loadingStatus" class="loader-main">
        <div class="loader"></div>
      </div>
      <div v-if="paymentStatus" class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="true"
                            :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true"/>
          <div class="row">
            <div class="col-lg-6 pr-[11px] my-3">


              <el-date-picker
                  v-model="user.start_date"
                  type="date"
                  placeholder="Select start date"
                  format="DD-MM-YYYY"
                  value-format="YYYY-MM-DD"

                  class="w-full custom-date-picker"
                  :clearable="true"
              />
            </div>
            <div class="col-lg-6 pr-[11px] my-3">


              <el-date-picker
                  v-model="user.end_date"
                  type="date"
                  placeholder="Select end date"
                  format="DD-MM-YYYY"
                  value-format="YYYY-MM-DD"

                  class="w-full custom-date-picker"
                  :clearable="true"
              />
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <summary-card class="h-[35rem]" mainTitle="Payment Details" firstHeading="Currency" :secondHeading="user.currency"
                        :detailsArray="billSummaryArray"/>
        </div>
        <div class="col-lg-6">
          <summary-card class="h-[35rem]" mainTitle="Payment Method" firstHeading="Method"
                        secondHeading="Bank" :detailsArray="bpayArray"/>
        </div>
        <div class="col-lg-12">
          <common-footer @back="handleBack" buttonTitle="Submit" @continue="handleContinue"/>
        </div>
      </div>
      <div v-else>
        <success-alert title='All Done'
                       :isBackButton="false"
                       desc='Funds Added Scuccesfuly'
                       btnText="Back To Dashboard"
                       @invite="backToDashboard" />
      </div>
    </div>
  </auth-layout>
</template>
<script>
import {
  TitleActionBar, SummaryCard, SummaryMainCard, CommonFooter , SuccessAlert
} from "@/components/index";

export default {
  components: {
    SuccessAlert,
    TitleActionBar,
    SummaryCard,
    SummaryMainCard,
    CommonFooter
  },
  data() {
    return {

      header: {
        title: "Summary",
        subTitle: "See your bill and BPAY summary"
      },
      billSummaryArray: [
        {title: "Frequency", detail: "Monthly"},

      ],
      bpayArray: [
        {title: "Account Number", detail: "123456789"},
        {title: "BSB", detail: "33-0000"}
      ],
      user: this.$inertia.form({
        type: "transfer",
        amount: null,
        currency:'',
        frequency: "",
        start_date: "",
        payment_method_id: null,
        end_date:null,
        receiver_id:null
      }),
      loadingStatus:false,
      paymentStatus:true,
    }
  },
  computed: {
    billSummaryArray() {
      const fundDetails = this.$store.getters.getFundDetail;
      return [
        {title: "Frequency", detail: fundDetails.frequency},
        {title: "Amount", detail: fundDetails.amount + '$'},

      ];
    },
    bpayArray() {
      const fundDetails = this.$store.getters.getPaymentMethodDetail;
      console.log(fundDetails)
      return [
        {title: "Account Title", detail: fundDetails.userName},
        {title: "Bank Name", detail: fundDetails.bankName },
        {title: "Account No.", detail: fundDetails.bankNumber },

      ];
    },

  },
  created() {
    this.assignValues();
    // this.$store.commit('SET_PAGE_COME_FROM', '');
  },
  methods: {
    assignValues() {

      const fundDetails = this.$store.getters.getFundDetail;
      this.user.amount = fundDetails.amount;
      this.user.frequency = fundDetails.frequency;
      this.user.currency = fundDetails.currency;
      this.assing()

    },
    assing(){
      const paymentDetails = this.$store.getters?.getPaymentMethodDetail;
      this.user.payment_method_id = paymentDetails.id;
    },
    handleBack() {
      window.history.back()
    },
    handleContinue() {
      if (!this.user.start_date) {
        this.$toast('Start date is required', 'error');
        return;
      }
      this.loadingStatus = true
      this.user.post('/add-deposit', {
        onSuccess: () => {
          this.$toast('Add fund successful', 'success');
          this.paymentStatus = false
        },
        onFinish: () => {
          this.loadingStatus = false;
        },
        onError: (error) => {
          this.showErrors(error);
          console.log('error hit')
          this.loadingStatus = false;
        }
      });
    },
    showErrors(errors) {
      if (errors && Object.keys(errors).length > 0) {
        Object.keys(errors).forEach(key => {
          this.$toast(`Error: ${errors[key]}`, 'error');
        });
      }
    },
    backToDashboard() {
      this.$inertia.visit('/dashboard')
    }
  }
}
</script>
<style scoped></style>