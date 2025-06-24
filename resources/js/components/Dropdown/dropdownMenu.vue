<template>
  <component :is="tag" class="dropdown" :class="{ show: isOpen }" @click="toggleDropDown"
    v-click-outside="closeDropDown">
    <div class="btn-rotate" :class="titleClasses" data-toggle="dropdown">
      <slot name="title">
        <span class="notification">{{ title }} &nbsp;
          <image-svg :width="17" :height="17" name="caret" />
        </span>
      </slot>
    </div>
    <ul class="dropdown-menu" :class="{ show: isOpen }">
      <slot></slot>
    </ul>
  </component>
</template>
<script>
import ImageSvg from '../ImageSvg/imageSvg.vue';
export default {
  components: {
    ImageSvg
  },
  props: {
    tag: {
      type: String,
      default: "li",
    },
    title: String,
    icon: String,
    titleClasses: [String, Object, Array],
  },
  data() {
    return {
      isOpen: false,
    };
  },
  methods: {
    toggleDropDown() {
      this.isOpen = !this.isOpen;
      this.$emit("change", this.isOpen);
    },
    closeDropDown() {
      this.isOpen = false;
      this.$emit("change", false);
    },
  },
};
</script>

<style scoped>
.notification {
  font-size: 16px;
  font-weight: 500;
  color: black;
  text-decoration: none;
  display: flex;
  align-items: center;
}

.dropdown-menu {
  padding: 5px 10px;
}
</style>
