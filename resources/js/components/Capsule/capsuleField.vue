<template>
  <div @click="handleCapsule" class="capsule px-2" :class="{
    borderColor:borderColor
  }"
    :style="{ padding: capPadding, height: capHeight, borderRadius: capRadius }">
    <div class="d-flex w-100 align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <image-svg :width="width" :height="height" :name="imageSource" /> &nbsp;
        <div class="field-width d-flex align-items-center ms-2">
          <!-- Render label as HTML -->
          <app-typography variant="body1" color="#76848D" fontWeight="600" v-html="label"></app-typography>
        </div>
      </div>
      <div class="me-2 align-items-center gap-2 d-flex">
        <image-svg v-if="isCompleted"  :width="15" :height="15" name="green-ticket-icon" class="icon" />
<!--        <image-svg :width="12" :height="12" name="cool-icon" />-->
      </div>
    </div>
  </div>
</template>

<script>
import ImageSvg from '../ImageSvg/imageSvg.vue';

export default {
  components: {
    ImageSvg
  },
  inheritAttrs: false,
  props: {
    label: {
      type: String,
      default: 'capsule lable'
    },
    imageSource: {
      type: String,
      default: 'step-profile'
    },
    height: {
      type: Number
    },
    width: {
      type: Number
    },
    capPadding: {
      type: String,
      default: '6px'
    },
    capRadius: {
      type: String,
      default: '46px'
    },
    capHeight: {
      type: String,
      default: '56px'
    },
    isCompleted:{
      type: Boolean,
      default: false
    },
    borderColor:{
      type: Boolean,
      default: false
    }
  },
  methods: {
    handleCapsule() {
      this.$emit('onclick')
    }
  }
};
</script>

<style>
.capsule {
  box-shadow: 0px 1.1px 4.97px 0px #00000014;
  justify-content: space-between;
  border: 1px solid #dbdbdb;
  margin-bottom: 25px;
  cursor: pointer;
  display: flex;
  width: 100%
}

.field-width {
  align-items: center;
  width: 90%;
}
.borderColor{
  border: 1px solid #4682be;
}
</style>
