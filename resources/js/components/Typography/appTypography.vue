<template>
  <div :class="[typographyClasses, bootstrapClasses]" :style="{ color: color, fontWeight: fontWeight }">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    variant: {
      type: String,
      default: "body1",
      validator: (value) =>
        ["h1", "h2", "h3", "h4", "h5", "h6", "body1", "body2", "body3", "body4", "text-60", "text-42", "text-38", "text-7", "text-8", "text-26"].includes(
          value
        ),
    },
    color: {
      type: String,
      default: "#36454f",
    },
    fontWeight: {
      type: [String, Number],
      default: "normal",
    },
    bootstrapClasses: {
      type: String,
      default: "",
    },
  },
  computed: {
    typographyClasses() {
      return {
        "text-40": this.variant === "h1",
        "text-32": this.variant === "h2",
        "text-28": this.variant === "h3",
        "text-24": this.variant === "h4",
        "text-20": this.variant === "h5",
        "text-18": this.variant === "h6",
        "text-16": this.variant === "body1",
        "text-14": this.variant === "body2",
        "text-12": this.variant === "body3",
        "text-10": this.variant === "body4",
        "text-60": this.variant === "text-60",
        "text-42": this.variant === "text-42",
        "text-38": this.variant === "text-38",
        "text-7": this.variant === "text-7",
        "text-8": this.variant === "text-8",
        "text-26": this.variant === "text-26",

      };
    },
  },
};
</script>
<style scoped>
.text-7 {
  font-size: 7px;
}

.text-8 {
  font-size: 8px;
}

.text-60 {
  font-size: 60px;
}

.text-42 {
  font-size: 42px;
}

.text-40 {
  font-size: 40px;
}

.text-38 {
  font-size: 38px;
}

.text-32 {
  font-size: 32px;
}

.text-28 {
  font-size: 28px;
}

.text-26 {
  font-size: 26px;
}

.text-24 {
  font-size: 24px;
}

.text-20 {
  font-size: 20px;
}

.text-18 {
  font-size: 18px;
}

.text-16 {
  font-size: 16px;
}

.text-14 {
  font-size: 14px;
}

.text-12 {
  font-size: 12px;
}

.text-10 {
  font-size: 10px;
}
</style>
