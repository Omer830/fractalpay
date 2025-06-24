<template>
  <auth-layout>
    <div class="container-fluid">
      <div class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                            :isActionButtons="false" :isUserName="false" :isDivider="true" />
        </div>
        <div class="col-lg-12">
          <deposite-funds-card :headingDetail="true" @continue="handleContinueClick" />
        </div>

      </div>
    </div>
  </auth-layout>
</template>

<script>
import { TitleActionBar, DepositeFundsCard, CommonFooter ,  } from "@/components/index.js";
export default {
  components: {
    DepositeFundsCard,
    CommonFooter,
    TitleActionBar,

  },
  data() {
    return {
      header: {
        title: "Wallet"
      }
    }
  },
  methods: {
    handleBackClick() {
      this.$inertia.visit('/dashboard')
    },
    handleContinueClick(value) {
      this.$store.commit('SET_PAGE_COME_FROM', 'deposit-funds');
      this.$store.commit('SET_ADD_FUND_DETAIL', value)
      this.$inertia.visit('/payment-method')
    }
  }
}
</script>

<style scoped></style>