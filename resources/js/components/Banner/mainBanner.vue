<template>
  <img class="width-50" :style="{ height: height }" :src="BANNER" alt="logo" />
</template>

<script>
import BANNER from '../../assets/images/banner.png';
export default {
  data() {
    return {
      BANNER: BANNER
    }
  },
  props: {
    height: {
      type: String,
      default: 'auto'
    }
  },
};
</script>

<style scoped>
/* .banner-wrapper {
  background-image: url("../../assets/images/banner-background.png");
  background-repeat: no-repeat;
  background-size: cover;
  height: 100vh;
}
.logo-wrapper {
  padding-top: 80px;
}
.divider {
  border: 1px solid #ffffff;
  width: 60%;
} */

.width-50 {
  width: 50vw;
}
</style>
