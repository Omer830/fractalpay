<template>

  <div class="col-lg-12">
    <payment-method :intent="intent" @continue="handleContinue"/>
    <div >
    <common-footer @back="handleBackClick" backButtonTitle="Continue" :isContinueBtn="cameFromDepositFund" @continue="handleContinueClick" />
    </div>
  </div>
</template>

<script>
import {CommonFooter, PaymentMethod} from "@/components/index.js";

export default {
  components: {
    CommonFooter,
    PaymentMethod
  },
  props: {
    intent: Object
  },
  data() {
    return {
      cameFromDepositFund: false

    }
  },
  mounted() {
    if (this.$store.getters.getPageComeFromName === 'deposit-funds') {
      this.cameFromDepositFund = true;
    }
  },
  methods: {
    handleContinue() {
      console.log('hit')
      // this.$router.push('/steps')
    },
    handleContinueClick(){
      this.$inertia.visit('/bpay-summary')
    },
    handleBackClick(){
      // this.$inertia.visit('/deposit-funds')
      // window.history.back()
      this.$inertia.visit('/steps')
    }
  }
}
</script>

<style scoped>

</style>