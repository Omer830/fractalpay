<template>
<!--    <logo-header :isBackBtn="false"/>-->
  <AuthLayout>
    <div class="container-fluid my-5">

        <invitation-method @icon-click="handleIconClick" :invitationTitle="title" :invitation-subtitle="subtitle" />
    </div>
    <common-footer @back="goBackHandler" :isContinueBtn="false" :isSendInvite="true" @sendinvite="handleInvite" />
  </AuthLayout>
</template>

<script>
import { InvitationMethod, LogoHeader, CommonFooter } from "@/components/index";

export default {
    components: {
        InvitationMethod,
        CommonFooter,
        LogoHeader
    },
    data() {
        return {
            title: "Select Invitation Method",
            subtitle: "QR Code, Email, SMS or WhatsApp",
        };
    },
    methods: {
        handleIconClick() {
            this.title = "Redirecting";
            this.subtitle = "Placeholder screen for WhatsApp, QR Code and SMS"
        },
        handleInvite() {
            this.$router.push('/success')
        },
        goBackHandler() {
          window.history.back();
        },
    },
};
</script>

<style scoped></style>