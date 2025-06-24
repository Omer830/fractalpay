<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="container-fluid mb-4">
      <div class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="true"
                            :subTitle="header.subTitle" :isSecondaryBtn="true"
                            :isActionButtons="true" :isUserName="false"
                            @secondary-click="handleViewDetail"
                            :isDivider="true" secondaryButtonText="Friends & Family"/>
        </div>
      <div>
        <ContributionTransactionDetailsCard :transactionDetails="transactionDetails"></ContributionTransactionDetailsCard>
      </div>
        <div class="col-lg-12">
          <common-footer @back="goBackHandler" :isContinueBtn="false"  />
        </div>
      </div>
    </div>
  </auth-layout>
</template>
<script>
import {
  TitleActionBar, SummaryCard, SummaryMainCard, CommonFooter , ContributionTransactionDetailsCard
} from "@/components/index";

export default {
  components: {
    TitleActionBar,
    SummaryCard,
    SummaryMainCard,
    CommonFooter,
    ContributionTransactionDetailsCard
  },
  data() {
    return {
      header: {
        title: "Contribution In",
        subTitle: ""
      },
      transactionDetails:null,
      loading: false,
    }
  },
  computed: {

  },
  mounted() {
    const urlParams = this.$store.getters.getContributorDetail;
    if(urlParams){
      this.id = urlParams.id;
      this.contributorType = urlParams.type;
      if(this.contributorType === 'outgoing'){
        this.header.title = 'Contribution Out'
      }else {
        this.header.title = 'Contribution In'
      }
    }else {
      this.handleBack()
    }

    this.fetchBillSummary()
  },
  methods: {

    handleBack() {
      window.history.back()
    },
    goBackHandler() {
      window.history.back()
    },
    fetchBillSummary() {
      this.loading = true;

      this.$inertia.post('/contribution-individual-detail', { user_id: this.id , contributer_type:this.contributorType }, {
        onSuccess: (page) => {

          this.transactionDetails = page.props.transactionDetails.data;

          this.loading = false;
        },
        onError: (errors) => {
          this.loading = false;
          console.error("An error occurred while submitting the bills:", errors);
          this.showErrors(errors);

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
    handleViewDetail(){
      this.$inertia.visit('/friend-and-family')
    }
  }
}
</script>
<style scoped></style>