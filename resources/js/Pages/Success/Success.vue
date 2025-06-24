<template>
  <logo-header :isBackBtn="false" />
  <success-alert :img="imgSrc" title="Invitation Sent"
    desc="Invite friends and family, invest  or setup contribution for other" :isBackButton="false"
    @invite="handleInviteAction" btnText="Continue" />
</template>
<script>
import { SuccessAlert, LogoHeader } from "@/components/index";
import SUCCESS_IMG from "../../assets/images/success.png";
export default {
  name: "success-screen",
  components: {
    SuccessAlert,
    LogoHeader
  },
  data() {
    return {
      imgSrc: SUCCESS_IMG
    }
  },
  methods: {
    handleInviteAction() {
      this.$router.push('/steps')
    }
  }
};
</script>

<style></style>
