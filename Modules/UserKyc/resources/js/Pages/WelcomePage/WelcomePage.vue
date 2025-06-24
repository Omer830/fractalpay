<template>
  <div class="container-fluid full-background">
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <logo-header :isBackBtn="false"  />

      <div class="d-flex justify-content-center">
      <image-svg class="text-center mx-auto"  name="welcome-text" />
      </div>
    <div class="content-div">
    <app-typography variant="h6" fontWeight="500" class="my-4">
      {{ welcomeText }}
    </app-typography>

<div class="d-flex justify-content-center">

  <div class="d-flex justify-content-center">
    <video
        width="828"
        height="324"
        controls
        src="../../../../../../resources/js/assets/images/welcome-video.mp4"
        class="welcome-video"
        title="Welcome Video"
        style="border-radius: 20px"
    >
      Your browser does not support the video tag.
    </video>
  </div>

</div>


      <div class="mt-3">
        <app-typography variant="h6" fontWeight="300" class="mt-4">
          Warm regards,
        </app-typography>
        <app-typography variant="h6" fontWeight="500" class="">
          The Fractal Pay Team
        </app-typography>
      </div>
    </div>
<div>
  <common-footer :isBackBtn="false"  @continue="handleSubmit"/>
</div>
  </div>
</template>

<script>
import { LogoHeader, CommonDocuments ,ReuseableButton} from "@components";
import BG_IMAGE from "../../../../../../resources/js/assets/images/welcom-screen-bg.png";
import ImageSvg from "../../../../../../resources/js/components/ImageSvg/imageSvg.vue";
import {AppTypography} from "@/components/index.js";
import CommonFooter from "@/components/CommonFooter/commonFooter.vue";

export default {
  components: {
    CommonFooter,
    AppTypography,
    ImageSvg,
    LogoHeader,
    CommonDocuments,
    ReuseableButton
  },
  data() {
    return {
      loading: false,
      iframeSrc: 'https://www.youtube.com/embed/IVAWBpeNAmE?si=UOK11A7BUe9Hm3s-',
      videoSrc: '../../../../../../resources/js/assets/images/welcome-video.mp4',
      welcomeText:'We\'re excited to have you with us. At Fractal Pay, your financial journey is our priority. Whether managing expenses, tracking investments, or simplifying payments, our app is here to support you.'
    };

  },

  methods:{
    handleSubmit(){
      this.$inertia.visit('steps')
    }

  },

};
</script>

<style scoped>
.full-background {
  background-image: url('../../../../../../resources/js/assets/images/welcom-screen-bg.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  width: 100%;
  height: 100vh;
}

.content-div {
  padding: 0 500px 40px;
  text-align: center;

}

</style>
