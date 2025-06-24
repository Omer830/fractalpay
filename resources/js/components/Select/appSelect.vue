<template>
  <div class="form-group">
    <slot name="label">
      <app-typography v-if="label" variant="body1" class="control-label mb-2">{{ label }}
        <span v-if="required" class="required-asterisk">*</span>
      </app-typography>
    </slot>
    <div class="position-relative">
      <el-select
          :class="['custom-select', borderRadiusClass, imageSource ? 'with-icon' : 'without-icon']"
          v-model="inputValue"
          placement="bottom-start"
          v-bind="$attrs"
          @input="updateInputValue($event.target.value)"
          :disabled="readOnly"
          @keydown.enter="handleEnterKey" placeholder="Select" class="custom-select">
        <el-option
            v-for="(option, index) in options"
            :key="index"
            v-bind="$attrs"
            :label="option[optionLabel]"
            :value="option[optionValue]">
        </el-option>
      </el-select>
      <div class="input-icon">
        <image-svg :width="width" :height="height" :name="imageSource" />
      </div>
    </div>
    <span v-if="error" class="error-message">{{ error }}</span>
  </div>
</template>

<script>
import { ElSelect, ElOption } from 'element-plus';
import ImageSvg from '../ImageSvg/imageSvg.vue';
import AppTypography from '../Typography/appTypography.vue';

export default {
  components: {
    ElSelect,
    ElOption,
    ImageSvg,
    AppTypography
  },
  props: {
    options: {
      type: Array,
      required: true
    },
    optionLabel: {
      type: String,
      default: 'label'
    },
    optionValue: {
      type: String,
      default: 'label'
    },
    placeholder: String,
    label: String,
    height: Number,
    width: Number,
    value: [String, Number],
    imageSource: String,
    error: String,
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
    }
  },
  data() {
    return {
      inputValue: this.value
    };
  },
  watch: {
    // value(newVal) {
    //     this.inputValue = newVal;
    // },
    // inputValue(newVal) {
    //     this.$emit('update:value', newVal);
    //     this.validateInput();
    // }
    borderRadiusClass(newVal) {
      console.log('Current borderRadiusClass:', newVal);
    }
  },
  mounted() {
    this.$nextTick(() => {
      // Manually re-calculate the dropdown positioning after render
      this.$refs.selectComponent?.updatePopper?.();
    });
  },
  methods: {
    // validateInput() {
    //     this.error = this.validateField(this.inputValue, this.validationRules);
    // },
    handleEnterKey() {
      this.$emit('input', this.inputValue);
    },
    updateInputValue(value) {
      this.inputValue = value;
    },
    validateField(value, rules) {
      for (const rule of rules) {
        if (rule.regex && !rule.regex.test(value)) {
          return rule.message;
        }
        if (rule.validator && !rule.validator(value)) {
          return rule.message;
        }
      }
      return '';  // No error
    }
  }
};
</script>

<style >
.required-asterisk {
  color: red;
}
.el-select__wrapper{
  height: 54px !important;
  border-radius: 10px !important;
}

.is-open {
  background-color: #F5F5F5;
  /* Example background color when select is open */
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
.input-icon {
  position: absolute;
  right: 12px;
  top: 12px;
}
.custom-select {
  cursor: pointer;
  appearance: none;
  /* Remove default arrow */
  background-image: url('data:image/svg+xml;utf8,<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="#000" d="M5.3 7.3l4.7 4.7 4.7-4.7a1 1 0 011.4 1.4l-5 5a1 1 0 01-1.4 0l-5-5a1 1 0 011.4-1.4z"/></svg>');
  /* Add custom arrow */
  background-repeat: no-repeat;
  background-position: right 12px center;
  /* Adjust the position of the arrow */
  border: none;

}
.el-select__wrapper{
  border-radius: 30px!important;
}
.custom-select:focus {
  outline: none;
  /* Remove focus outline */
  border-color: transparent;
  /* Remove border color on focus */
}
.error-message {
  color: red;
}
.border-lg .el-select__wrapper {
  border-radius: 46px !important;
}
.border-sm .el-select__wrapper {
  border-radius: 10px !important;
}

.el-select__wrapper {
  min-height: 54px !important;
  font-size: 1rem !important;
  border-right: 30px !important;
  border-radius: 8px !important;
}
.with-icon .el-select__wrapper {
  padding-right: 3rem !important; /* Larger padding when icon is present */
}

.without-icon .el-select__wrapper {
  padding-right: 1rem !important; /* Smaller padding when icon is not present */
}

#el-popper-container-4298{
  position: relative;
}
</style>
