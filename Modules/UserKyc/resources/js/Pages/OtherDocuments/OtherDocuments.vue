<template>
    <div class="container-fluid">
      <div v-if="loading" class="loader-main">
        <div class="loader"></div>
      </div>
        <logo-header @back-clicked="handleBackClick" />
        <common-documents
            title="Other Documents"
            :kycStatus="kycStatus"
            :cardsData="cardsData.data.documents"
            @card-clicked="onCardClicked"
            @continue="handleContinue" />
    </div>
</template>

<script>
import { LogoHeader, CommonDocuments } from "@components";

export default {
    components: {
        LogoHeader,
        CommonDocuments,
    },
  props: {
    cardsData: Array,
    kycStatus:Object,
  },
    data() {
        return {
          loading:false,
            // cardsData: [
            //     {
            //         image: { name: "mortgage-loan-icon", height: "52", width: "52" },
            //         title: "A mortgage or other instrument of security held by a financial body",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "document-icon", height: "52", width: "52" },
            //         title: "Document from current or previous employer within the last two years",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "cash-icon", height: "52", width: "52" },
            //         title: "Document held by a cash dealer giving security over property",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "office-record-icon", height: "52", width: "52" },
            //         title: "Land Titles Office record",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "ballot-lock-icon", height: "52", width: "52" },
            //         title: "Council rates notice",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "checking-icon", height: "52", width: "52" },
            //         title: "Document form the Credit Reference",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "debit-card-icon", height: "52", width: "64" },
            //         title: "Current credit card or account card from a bank, building society or credit union",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "agrement-icon", height: "52", width: "52" },
            //         title: "Lease / rent agreement",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "telephone-icon", height: "52", width: "52" },
            //         title: "Current telephone, water, gas or electricity bill",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "wfh-icon", height: "52", width: "52" },
            //         title: "Current rent receipt from a licensed real estate agent",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "driving-exam-icon", height: "52", width: "52" },
            //         title: "Foreign driver’s licence",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "book-icon", height: "52", width: "52" },
            //         title: "Records of a primary, secondary or tertiary education institution attended by theapplicant within the last 10 years",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "medical-card-icon", height: "52", width: "52" },
            //         title: "Medicare card",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "book-icon", height: "52", width: "52" },
            //         title: "Records of a professional or trade association of which the applicant is amember",
            //         points: "35 points",
            //     },
            //     {
            //         image: { name: "atom-electro-icon", height: "52", width: "52" },
            //         title: "Electoral roll compiled by electoral Commission",
            //         points: "35 points",
            //     },
            // ],
        };
    },
  mounted() {
    this.getKycStatus()
  },
    methods: {
        handleBackClick() {
          this.$inertia.visit('/doc-type')
        },
        onCardClicked(data) {
        console.log('Card clicked:', data);
        this.$inertia.visit('/document-upload')
      },
        handleContinue() {
          this.$toast('Please upload Document', 'error');
          this.$inertia.visit('/steps')
        },

      getKycStatus() {
        this.loading = true;
        this.$inertia.get('/other-documents', {}, {
          preserveState: true,
          preserveScroll: true,
          onSuccess: (page) => {
            this.loading = false;
            console.log("KYC Status updated", JSON.stringify(page.props.kycStatus));
            this.kycStatus = page.props.kycStatus;
          }
        });
      }

    },
};
</script>

<style scoped></style>