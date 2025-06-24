<template>
  <app-typography fontWeight="700" variant="h4">Enter Email Verification Code</app-typography>
  <app-typography fontWeight="500" variant="body2">We have sent a 4-digit code to your <span
      v-if="isEmail">email</span><span v-if="!isEmail">phone</span></app-typography>
  <app-typography v-if="isEmail" class="mb-2" color="#4682BEE5" fontWeight="500"
                  variant="body2">{{$store.getters.getEmail}}
  </app-typography>
  <app-typography v-if="!isEmail" class="mb-2" color="#4682BEE5" fontWeight="500"
                  variant="body2">03222222222
  </app-typography>
  <div class="otp-input">
    <input v-for="(digit, index) in otpDigits" :key="index" :ref="`otpInput${index}`" v-model="otpDigits[index]" maxlength="1"
           type="text" @input="validateNumericInput(index)"
           @keydown="handleKeyPress($event, index)"
           @focus="clearOTPError"
           @paste="handlePaste"/>
  </div>
  <!-- Error message below OTP field -->
  <p v-if="user.errors.otp || otpError" class="error-message">
    {{ user.errors.otp || otpError }}
  </p>

  <reuseable-button :width="100" @click="verifyOtp" :disabled="loading">
    {{ loading ? 'Verifying...' : 'Confirm' }}
  </reuseable-button>

  <app-typography class="my-2" color="#76848D" fontWeight="500" variant="body3">
    <span v-if="timer > 0">Resend code in {{ timer }} seconds</span>
    <a v-else href="#" @click="resendOTP" style="color: #4682BE;">Resend</a>
  </app-typography>
  <back-button v-if="isBackBtn" :startIcon="true" @back="handleBack"/>
</template>

<script>
import { AppTypography, BackButton, ReuseableButton } from "@/components/index.js";

export default {
  data() {
    return {
      loading: false,
      otpDigits: ['', '', '', ''], // Initialize with empty values for each digit
      user: this.$inertia.form({
        otp: '',
        email: '',
      }),
      timer: 60, // Timer starts at 60 seconds
      otpError: '', // Error message for OTP validation
    };
  },
  props: {
    isEmail: {
      type: Boolean,
      default: true,
    },
    errors: {
      type: Object,
      default: () => ({})
    },
    isBackBtn: {
      type: Boolean,
      default: true,
    }
  },
  components: {
    ReuseableButton,
    AppTypography,
    BackButton
  },
  watch: {
    'user.errors': {
      deep: true,
      handler(newVal) {
        if (newVal.otp) {
          this.clearOTP();
          this.loading = false;
        }
        console.log("Errors updated:", newVal);
      }
    }
  },
  mounted() {
    this.startTimer(); // Start the timer when the component is mounted
  },
  methods: {
    startTimer() {
      const interval = setInterval(() => {
        if (this.timer > 0) {
          this.timer--;
        } else {
          clearInterval(interval); // Stop the timer when it reaches 0
        }
      }, 1000); // Decrease the timer every second
    },
    clearOTP() {
      this.otpDigits = ['', '', '', ''];
    },
    handleBackspace(event, index) {
      if (event.key === 'Backspace' && this.otpDigits[index] === '') {
        if (index > 0) {
          this.$refs[`otpInput${index - 1}`][0].focus();
        }
      }
    },
    validateNumericInput(index) {
      const value = this.otpDigits[index];
      // Allow only numeric values
      if (!/^\d$/.test(value)) {
        this.otpDigits[index] = ''; // Clear invalid input
      } else {
        this.focusNextInput(index); // Automatically move to the next input
      }
    },
    handlePaste(event) {
      const pastedData = event.clipboardData.getData('text');
      if (/^\d{4}$/.test(pastedData)) { // Check if the pasted data is exactly 4 digits
        pastedData.split('').forEach((digit, index) => {
          if (index < this.otpDigits.length) {
            this.otpDigits[index] = digit;
          }
        });
        // Focus the last input field after pasting
        this.$refs[`otpInput${this.otpDigits.length - 1}`][0].focus();
      } else {
        this.$toast('Please paste a valid 4-digit OTP.', 'error'); // Show error for invalid paste
      }
    },
    focusNextInput(index) {
      if (index < this.otpDigits.length - 1 && this.otpDigits[index] !== '') {
        this.$refs[`otpInput${index + 1}`][0].focus();
      }
    },
    handleKeyPress(event, index) {
      if (event.key === 'Enter') {
        this.verifyOtp(); // Call verifyOtp when Enter key is pressed
      } else if (event.key === 'Backspace') {
        this.handleBackspace(event, index); // Handle backspace functionality
      }
    },
    verifyOtp() {
      const otpString = this.otpDigits.join('');
      const otpNumber = Number(otpString);
      if (isNaN(otpNumber) || otpString.length !== 4) {
        this.otpError = 'Please enter a 4-digit OTP'; // Set error message
        return;
      }
      this.otpError = ''; // Clear error message if validation passes
      this.loading = true;
      this.user.otp = otpNumber;
      this.user.email = this.$store.getters.getEmail;
      this.$store.commit('SET_OTP', this.user.otp);
      this.user.post('/verifyOTPFP', {
        otp: this.user.otp,
        email: this.user.email
      }, {
        onFinish: () => this.loading = false,
        onError: () => {
          this.loading = false;
        },
        onSuccess: (response) => {
          if (response.props.success) {
            this.$inertia.visit('/steps');
          }
        }
      });
    },
    handleBack() {
      this.$inertia.visit('/forgot-password');
    },
    clearOTPError() {
      this.otpError = ''; // Clear error message when input is focused
      this.user.errors.otp = ''; // Clear server-side error
    },
    resendOTP() {
      this.clearOTPError();
      this.timer = 60; // Reset the timer when the OTP is resent
      this.startTimer(); // Restart the timer
      const email = this.$store.getters.getEmail;
      if (!email) {
        console.error("No email found in Vuex store.");
        return;
      }
      let form = this.$inertia.form({ email });
      form.post('/sendOTP', {
        preserveState: true,
        onFinish: () => {
          this.$toast('OTP resent successfully', 'success');
        },
        onError: (errors) => {
          console.log("Errors during OTP resend:", errors);
        },
        onSuccess: (response) => {
          if (response.props.success) {
            console.log("OTP resent successfully");
          }
        }
      });
    },
  }
};
</script>

<style scoped>
.otp-input {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

input[type="text"] {
  width: 60px;
  height: 60px;
  font-size: 20px;
  text-align: center;
  margin: 0 5px;
  border-radius: 50%;
  border: 1px solid #EEEEEE;
}

/* Change border color when focused */
input[type="text"]:focus {
  border-color: #4682BE;
}
.error-message {
  color: red;
  text-align: center;
}
</style>
