<template>
    <div class="container-fluid">
      <div v-if="loading" class="loader-main">
        <div class="loader"></div>
      </div>
        <logo-header @back-clicked="handleBackClick" />
        <common-documents
            title="Secondary Documents"
            @card-clicked="onCardClicked"
            :kycStatus="kycStatus"
            :cardsData="cardsData.data.documents" @continue="handleContinue" />
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
            //         image: { name: "taxi-driver-icon", height: "46", width: "63" },
            //         title: "Current driver photo licence issued by state or territory",
            //         points: "40 points",
            //     },
            //     {
            //         image: { name: "identity-card-icon", height: "53", width: "65" },
            //         title: "Identification card issued to a student at a tertiary education institution",
            //         points: "40 points",
            //     },
            //     {
            //         image: { name: "identity-card-icon", height: "53", width: "65" },
            //         title: "Identification card issued to a public employee",
            //         points: "40 points",
            //     },
            //     {
            //         image: { name: "identity-card-icon", height: "53", width: "65" },
            //         title: "Identification card issued by the Australian or any state government as evidenceof a person’s entitlement to a financial benefit",
            //         points: "40 points",
            //     },
            //
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
          // this.$inertia.visit('/steps')
        },
      getKycStatus() {
        this.loading = true;
        this.$inertia.get('/secondary-documents', {}, {
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