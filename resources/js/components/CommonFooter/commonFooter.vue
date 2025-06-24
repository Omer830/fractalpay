<template>
  <footer class="py-3">
    <hr v-if="divider" />
    <div class="p-2">
      <div class="d-flex gap-3 flex-wrap w-100 justify-content-end">
        <reuseable-button  v-if="isBackBtn" @click="goBack" textColor="#4682BE" class="continue-btn" outline round="md"
          btnHeight="62px">
          {{ backButtonTitle }}
        </reuseable-button>
        <reuseable-button v-if="isContinueBtn" @click="goContinue" class="primary-btn  continue-btn" round="md"
          btnHeight="62px">
          {{ buttonTitle }}
        </reuseable-button>
        <reuseable-button v-if="isSendInvite" @click="handleSendInvite" class="primary-btn  continue-btn" round="md"
          btnHeight="62px">
          Send Invite
        </reuseable-button>
        <reuseable-button v-if="isSaveBtn" @click="handleSave" class="primary-btn  continue-btn" round="md"
          btnHeight="62px">
          Save
        </reuseable-button>
      </div>
    </div>
  </footer>
</template>

<script>
import { ReuseableButton } from "@/components/index";

export default {
  components: {
    ReuseableButton,
  },
  props: {
    isBackBtn: {
      type: Boolean,
      default: true,
    },
    isContinueBtn: {
      type: Boolean,
      default: true,
    },
    divider: {
      type: Boolean,
      default: true
    },
    isSendInvite:{
      type:Boolean,
      default:false
    },
    isSaveBtn:{
      type:Boolean,
      default:false
    },
    buttonTitle:{
      type:String,
      default: 'Continue'
    },
    backButtonTitle:{
      type:String,
      default: 'Back'
    }

  },
  methods: {
    goBack() {
      this.$emit("back");
    },
    goContinue() {
      this.$emit("continue");
    },
    handleSendInvite(){
      this.$emit('sendinvite')
    },
    handleSave(){
      this.$emit('saveBtn')
    }
  },
};
</script>

<style scoped>
  .continue-btn,
  .back-btn {
    width: 180px;
  }

@media screen and (max-width: 475px) {

  .continue-btn,
  .back-btn {
    width: 100%;
  }
}
</style>
