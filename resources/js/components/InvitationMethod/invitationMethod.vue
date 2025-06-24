<template>
  <div class="invitation-wrapper container">
    <div class="card">
      <div class="card-header bg-white text-center">
        <app-typography variant="h4" fontWeight="500">{{
          invitationTitle
        }}</app-typography>
        <app-typography variant="body1" color="#777E90">{{
          invitationSubtitle
        }}</app-typography>
      </div>
      <div v-if="showCardBody" class="card-body d-flex justify-content-center align-items-center gap-3 flex-wrap">
        <div v-for="(icon, index) in iconsArray" :key="index"
          class="icon-wrapper d-flex justify-content-center align-items-center">
          <div @click="handleIconClick(index)" class="pointer">
            <image-svg :width="icon.width" :height="icon.height" :name="icon.name" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { AppTypography } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
  components: {
    AppTypography,
    ImageSvg
  },
  props: {
    invitationTitle: {
      type: String,
      default: "Select Invitation Method",
    },
    invitationSubtitle: {
      type: String,
      default: "QR Code, Email, SMS or WhatsApp",
    },
    iconsArray: {
      type: Array,
      default: () => [{ name: "sms", height: 50, width: 49 }, { name: "gmail", height: 42, width: 56 }, { name: "qrcode", height: 51, width: 67 }, { name: "whatsapp", height: 50, width: 50 }],
    },
  },
  data() {
    return {
      showCardBody: true,
    };
  },
  methods: {
    handleIconClick(index) {
      if (index === 1) {
        this.$inertia.visit('/invite-friends-family')
      } else {
        this.showCardBody = !this.showCardBody;
        this.$emit("icon-click");
      }
    },
  },
};
</script>

<style scoped>
.card {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  border-radius: 6px;
  height: 700px;
  border: none;
}

.card-header {
  padding: 33px;
}

.card-body {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  margin: 0px 170px 50px 170px;
  border-radius: 6px;
}

@media screen and (max-width: 998px) {
  .card-body {
    margin: 0 30px 0 30px;
  }
}

.icon-wrapper {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  border-radius: 50%;
  height: 100px;
  width: 100px;
}
</style>
