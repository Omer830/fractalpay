<template>
  <div class="main-wrapper mb-5">
    <div class="header d-flex justify-content-center">
      <div class="logo">
        <image-svg :width="305" :height="78" name="logo" />
      </div>

    </div>
    <div v-if="hasDivider" class="p-0 mx-4">
      <hr />
    </div>
    <div v-if="isBackBtn" class="back d-flex cursor-pointer flex-start px-3">
      <back-button startIcon @click="handleBackClick" />
    </div>
  </div>
</template>

<script>
import { BackButton } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
  name: "logo-header",
  components: {
    BackButton,
    ImageSvg
  },
  props: {
    isBackBtn: {
      type: Boolean,
      default: true,
    },
    hasDivider: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    handleBackClick() {
      this.$emit("back-clicked");
    },
  },
};
</script>

<style scoped>
.logo {
  padding: 50px 0px;
}

hr {
  color: #4682BE
}
.cursor-pointer {
  cursor: pointer;
}
</style>
