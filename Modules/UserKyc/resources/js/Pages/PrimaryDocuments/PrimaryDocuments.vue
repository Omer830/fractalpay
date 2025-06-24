<template>
  <div class="container-fluid">
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <logo-header @back-clicked="handleBackClick" />
    <common-documents
        :cardsData="cardsData?.data?.documents"
        :kycStatus="kycStatus"
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
  inheritAttrs: false,
  props: {
    cardsData: Object,
    kycStatus:Object,
  },
  data() {
    return {
      loading:false,

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
    handleContinue(){
      if(this.kycStatus.data.total_points >= 100){
        // if(this.$store.getters.getDocType === "Primary Documents" ){
        //   this.$inertia.visit('/primary-documents')
        // }
        this.$inertia.visit('/steps')
      }else {
        this.$toast('Please upload Document', 'error');
      }
      // this.$toast('Please select Document', 'error');

      //
    },
    getKycStatus() {
      this.loading = true;
      this.$inertia.get('/primary-documents', {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
          this.loading = false
          this.kycStatus = page.props.kycStatus;

        }
      });
    }

  },
};
</script>

<style></style>
