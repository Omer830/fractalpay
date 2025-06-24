<template>
  <div class="title-wrapper d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <div class="d-flex gap-2 flex-wrap">
        <app-typography :variant="textVariant" :fontWeight="textWeight">{{ title }}</app-typography>
        <app-typography v-if="isUserName" variant="h2" fontWeight="500" color="#0C266C">{{
            userName
          }}
        </app-typography>
      </div>
      <app-typography v-if="isSubTitle" variant="h6" fontWeight="500" color="#777E90">{{
          subTitle
        }}
      </app-typography>
    </div>

    <div v-if="buttonArray.length>0">
      <div class="toggle-container" :style="{ width: containerWidth + 'px' }">
        <!-- The sliding toggle button -->
        <div
            class="toggle-button"
            :style="{ left: activeButton * buttonWidth + 'px' }"
        ></div>

        <!-- Labels for buttons -->
        <div class="role-labels">
      <span
          v-for="(button, index) in buttonArray"
          :key="index"
          :class="{ active: activeButton === index }"
          @click="handleToggle(index,button)"
      >
        {{ button.label }}
      </span>
        </div>
      </div>
    </div>

    <div v-if="isActionButtons" class="d-flex gap-3 flex-wrap btn-wrapper">
      <reuseable-button @click="handleTertiaryBtn" v-if="isTertiaryBtn" :round="btnRound" outline
                        textColor="#4682BE" class="actionBtn">
        {{ tertiaryButtonText }}
      </reuseable-button>
      <reuseable-button @click="handleSecondaryBtn" v-if="isSecondaryBtn" :round="btnRound" outline
                        textColor="#4682BE" class="actionBtn">
        {{ secondaryButtonText }}
      </reuseable-button>
      <reuseable-button @click="handlePrimaryBtn" v-if="isPrimaryBtn" :round="btnRound" class="actionBtn">
        {{ primaryButtonText }}
      </reuseable-button>
    </div>
    <div v-if="isHelp">
      <image-svg width="31px" height="31px" name="interogative-mark-icon"/>
    </div>
  </div>
  <hr v-if="isDivider"/>
</template>
<script>
import {AppTypography, ReuseableButton, ImageSvg} from "@/components/index";

export default {
  components: {
    AppTypography,
    ReuseableButton,
    ImageSvg
  },
  data() {
    return {
      // Active button index
      activeButton: 0,

    };
  },
  props: {
    textVariant: {
      type: String,
      default: 'h2'
    },
    textWeight: {
      type: String,
      default: '400'
    },
    btnRound: {
      type: String,
      default: 'lg'
    },
    title: {
      type: String,
      default: 'Welcome,'
    },
    isUserName: {
      type: Boolean,
      default: true
    },
    userName: {
      type: String,
      default: 'Harold Johnson'
    },
    isSubTitle: {
      type: Boolean,
      default: true
    },
    subTitle: {
      type: String,
      default: 'Your Journey to Healthcare Finance starts here'
    },
    isActionButtons: {
      type: Boolean,
      default: false
    },
    isSecondaryBtn: {
      type: Boolean,
      default: false
    },
    isPrimaryBtn: {
      type: Boolean,
      default: false
    },
    isTertiaryBtn: {
      type: Boolean,
      default: false
    },
    isDivider: {
      type: Boolean,
      default: false
    },
    tertiaryButtonText: {
      type: String,
      default: 'Deposit Funds'
    },
    secondaryButtonText: {
      type: String,
      default: 'Deposit Funds'
    },
    primaryButtonText: {
      type: String,
      default: 'Invite'
    },
    isHelp: {
      type: Boolean,
      default: false
    },
    buttonArray: {
      type: Array,
      default: () => []
    }
  },
  emits: ['primaryClick', 'secondaryClick', 'FILTERED_LIST', 'selection-changed', 'contributor-click','TOGGLE_BUTTON_CLICK'],
  computed: {
    containerWidth() {
      return this.buttonArray?.length * 140; // 100px for each button width
    },
    buttonWidth() {
      return 140; // Each button will take up 100px of space
    },
  },
  mounted() {
    if (this.$store.getters.getExpenseType === 'Share with me') {
      console.log(this.$store.getters.getExpenseType , 'hitt')
      this.handleToggle(1,{"label":"Share with me","textColor":"#4682BE","outline":true})

    }
  },
  watch: {
    '$store.getters.getExpenseType': {
      handler(newValue) {
        if (newValue === 'Share with me') {
          this.handleToggle(1,{"label":"Share with me","textColor":"#4682BE","outline":true})
        }
      },
      immediate: true // Mounted pe bhi call ho jayega
    }
  },
  methods: {
    handleSecondaryBtn() {
      this.$emit('secondaryClick')
    },
    handlePrimaryBtn() {
      this.$emit('primaryClick')
    },
    handleTertiaryBtn() {
      this.$emit('tertiary-click')
    },
    handleToggle(index, button) {
      console.log(index, JSON.stringify(button))
      this.activeButton = index;
      this.$emit('TOGGLE_BUTTON_CLICK', button)
    },
  }
}
</script>

<style scoped>
.title-wrapper {
  padding: 27px 0px;
}

.actionBtn {
  width: 220px;
}

hr {
  color: #4682BE;
  margin: 0px;
}

@media screen and (max-width: 475px) {
  .actionBtn {
    width: 100%;
  }

  .btn-wrapper {
    width: 100%;
  }
}

.toggle-container {
  display: flex;
  align-items: center;
  border-radius: 40px;
  border: 1px solid #d1d1d1;
  position: relative;
  height: 45px;
  background-color: #f8f9fa;
  overflow: hidden;
}

.toggle-button {
  width: 140px; /* Set width to match the button size */
  height: 39px;
  background-color: #4682BE;
  border-radius: 18px;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: left 0.3s ease;
}

.role-labels {
  width: 100%;
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  font-weight: bold;
  color: #4682BE;
  z-index: 1;
  position: relative;
}

.role-labels span {
  cursor: pointer;
  flex: 1;
  text-align: center;
  z-index: 2;
  transition: color 0.3s ease-in-out;
}

.role-labels span.active {
  color: #fff;
}
</style>