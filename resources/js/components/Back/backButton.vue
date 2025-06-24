<template>
  <div @click="handleBack" class="pointer text-center d-flex justify-content-center align-items-center gap-2">
    <template v-if="startIcon">
      <image-svg :width="width" :height="height" :name="image" />
    </template>
    <app-typography :variant="textVariant" :color="textColor" :font-weight="fontWeight" :style="{ fontSize: fontSize }">
      {{ btnText }}
    </app-typography>
    <template v-if="endIcon">
      <image-svg :width="width" :height="height" :name="image" />
    </template>
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
    btnText: {
      type: String,
      default: "Back",
    },
    image: {
      type: String,
      default: "back",
    },
    width: {
      type: [String, Number],
      default: '25px',
    },
    height: {
      type: [String, Number],
      default: '25px',
    },
    fontSize: {
      type: String,
      default: "18px",
    },
    textVariant: {
      type: String,
      default: "h4",
    },
    textColor: {
      type: String,
      default: "#76848D",
    },
    fontWeight: {
      type: [String, Number],
      default: 400,
    },
    startIcon: {
      type: Boolean,
      default: false,
    },
    endIcon: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    handleBack() {
      this.$emit('back')
    }
  }

};
</script>

<style scoped></style>
