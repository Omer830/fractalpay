<template>

  <div class="col-lg-12">
<!--    <payment-method :intent="intent" @continue="handleContinue"/>-->
    <common-footer @back="handleBackClick" @continue="handleContinueClick" />
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
    methods:{
        handleContinue(){
            console.log('hit')
            // this.$router.push('/steps')
        }
    }
}
</script>

<style scoped>

</style>