<template>

  <div>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
  <invite-friends-and-family v-if="!isSend" @send="handleSend" @modal-back="handleModalBack"/>
    <div v-else>
      <logo-header :isBackBtn="false" @back-clicked="handleBack" />
      <success-alert :title='alertTitle' @invite="handleInvite" @back="handleBackToDashboard" :desc='alertDesc' :isBackButton='true' />
    </div>

  </div>
</template>

<script>
import {InviteFriendsAndFamily} from "@/components/index";
import SuccessAlert from "@/components/SuccessAlert/successAlert.vue";
import { LogoHeader, AppTypography, FormGroupInput, ReuseableButton } from "@/components/index";
export default {
  components: {
    SuccessAlert,
    InviteFriendsAndFamily,
    LogoHeader,
  },
  data() {
    return {
      user: this.$inertia.form({
        email: [],

      }),
      isSend: false,
      loading: false,
      alertTitle: 'Invitation Sent',
      alertDesc: 'Invite friends and family, invest or setup contribution for other',
    }
  },
  methods: {
    handleSend(newEmails) {
      this.loading = true
      this.user.email = newEmails;

      console.log(JSON.stringify(newEmails))
      this.user.post('/v1/invitation/sendEmails', {
        onSuccess: () => {
          this.$toast('Invitations sent successfully!', 'success');
          this.user.reset('emails');
          this.isSend = true
          this.loading = false
        },
        onError: errors => {
          console.log('Error sending invitations:', errors);
          this.showErrors(errors);
          this.loading = false

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
    handleModalBack() {
      window.history.back();
      // Add your logic for handling the back action here
    },
    handleBack() {
      window.history.back();
      // Add  yourlogic for handling the back action here
    },
    handleBackToDashboard(){
      this.$inertia.visit('/dashboard')
    },
    handleInvite(){
      this.isSend = false
    }
  }
}
</script>
<style scoped></style>