<template>
  <component :is="tag" :type="nativeType" :disabled="disabled || loading" :class="[
    'btn',
    { 'primary-btn': !outline },
    { 'outline-btn': outline },
    { 'primary-btn': disabled },

    { 'btn-block': block },
    { 'btn-round-lg': round === 'lg' },
    { 'btn-round-md': round === 'md' },
    { 'btn-round-sm': round === 'sm' },
    { 'btn-just-icon': icon && !text },
    { 'btn-icon-text': icon && text },
    'd-flex',
    'justify-content-center',
    'align-items-center',
    widthClass,
  ]" :style="{ fontSize: fontSize, height: btnHeight, color: textColor }" @click="handleClick">
    <slot name="start-icon">
      <div v-if="startIcon" class="icon me-2">
        <image-svg :width="imageWidth" :height="imageHeight" :name="startIcon" />
      </div>
    </slot>
    <slot></slot>
    <slot name="end-icon">
      <div v-if="endIcon" class="icon ms-2">
        <image-svg :width="imageWidth" :height="imageHeight" :name="endIcon" />
      </div>
    </slot>
    <slot name="loading">
      <i v-if="loading" class="fa fa-spinner fa-spin"></i>
    </slot>
  </component>
</template>

<script>
import ImageSvg from '../ImageSvg/imageSvg.vue';

export default {
  props: {
    fontSize: {
      type: String,
      default: '18px'
    },
    tag: {
      type: String,
      default: "button",
    },
    btnHeight: {
      type: String,
      default: "56px",
    },
    textColor: {
      type: String,
      default: "#fff",
    },
    outline: {
      type: Boolean
    },
    block: {
      type: Boolean
    },
    loading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    startIcon: {
      type: String
    },
    endIcon: {
      type: String
    },
    width: {
      type: String
    },
    imageHeight: {
      type: Number
    },
    imageWidth: {
      type: Number
    },
    text: {
      type: Boolean
    },
    round: {
      type: String,
      validator: function (value) {
        return ["lg", "md", "sm"].includes(value);
      },
      default: "lg",
    },
    nativeType: {
      type: String,
      default: "button",
    },
    icon: {
      type: String,
      default: ''
    },
  },
  computed: {
    widthClass() {
      return this.width ? `w-${this.width}` : "";
    },
  },
  methods: {
    handleClick() {
      this.$emit("click");
    },
  },
  components: {
    ImageSvg
  }
};
</script>

<style scoped>
.primary-btn,
.outline-btn {
  background-color: #4682be;
  font-weight: 500;
  color: #ffff;
  border: none;
}

.outline-btn {
  background-color: transparent;
  border: 1px solid #4682be;
  color: #4682be !important;
}

.btn-round {
  border-radius: 50px;
}

.btn-just-icon {
  padding: 0;
}

.btn-icon-text {
  padding-right: 10px;
  padding-left: 10px;
}

.btn-round-lg {
  border-radius: 50px;
}

.btn-round-md {
  border-radius: 25px;
}

.btn-round-sm {
  border-radius: 15px;
}
.btn:disabled{
  background: #4682be;
}
.btn:hover{
  background: #4682be;
  color: white !important;
}
.btn:first-child:active{
  background: #4682be;
}

</style>
