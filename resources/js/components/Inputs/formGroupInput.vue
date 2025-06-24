<template>
  <div class="form-group">
    <slot name="label">
      <app-typography v-if="label" variant="body1" class="control-label mb-2">{{ label }}
        <span v-if="required" class="required-asterisk">*</span>
      </app-typography>
    </slot>
    <div>
      <div class="position-relative">

        <input
            :type="inputType"
            :value="value"
            v-model="inputValue"
            @input="updateInputValue($event.target.value)"
            v-bind="$attrs"
            :maxlength="maxlength"
            :class="['form-control custom-input align-items-center', borderRadiusClass , { 'read-only-input': readOnly }]"
            @blur="validateInput"
            @keydown.enter="handleEnterKey"
            :readonly="readOnly"
        />
        <div class="input-icon" :style="{ cursor: type === 'password' ? 'pointer' : 'default' }" @click="togglePasswordVisibility">
          <image-svg :width="width" :height="height" :name="imageSource"/>
        </div>
      </div>
    </div>
    <span v-if="error" style="color: red;">{{ error }}</span>
  </div>
</template>

<script>
import ImageSvg from '../ImageSvg/imageSvg.vue';
import AppTypography from '../Typography/appTypography.vue';
// import {readonly} from "vue";

export default {
  components: {
    ImageSvg,
    AppTypography
  },
  inheritAttrs: false,
  props: {
    label: String,
    height: Number,
    width: Number,
    value: [String, Number],
    imageSource: String,
    type: String,
    error: String,  // Receive error prop
    borderRadiusClass: {
      default: "border-lg",
    },
    validationRules: {
      type: Array,
      default: () => []
    },
    required: {
      type: Boolean,
      default: false
    },
    readOnly: {
      type: Boolean,
      default: false
    },
    maxlength: Number,
  },
  data() {
    return {
      inputValue: '',
      errorMessage: '',
      inputType: this.type
    };
  },
  methods: {
    updateInputValue(value) {
      this.inputValue = value;
    },
    validateField(value, rules) {
      if (!rules) return ''; // If rules are undefined, return empty string

      for (const rule of rules) {
        if (rule.regex) {
          if (!rule.regex.test(value)) {
            return rule.message;
          }
        }
        if (rule.validator) {
          if (!rule.validator(value)) {
            return rule.message;
          }
        }
      }
      return ''; // Empty string indicates no error
    },
    togglePasswordVisibility() {
      if (this.type === 'password') {
        this.inputType = this.inputType === 'password' ? 'text' : 'password';
      }
    },
    handleEnterKey() {
      this.$emit('input', this.inputValue);
    }
  }
};
</script>

<style scoped>

.required-asterisk {
  color: red;
}
.form-control {
  display: flex;
  justify-content: space-between;
  height: 54px;
}

.border-lg {
  border-radius: 46px;
}

.border-sm {
  border-radius: 10px;
}

.inner-field {
  background-color: transparent;
  border: none;
  width: 90%;
}

textarea:focus,
input:focus {
  outline: none;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  display: none;
}

.input-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
}

.custom-input {
  padding-right: 3rem;
}
.read-only-input {
  cursor: not-allowed;
  background-color: #f9f9f9;
}
</style>
