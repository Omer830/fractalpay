<template>
  <div class="choose-wrapper">
    <logo-header :isBackBtn="false"/>
    <div class="form-wrapper">
      <div v-if="!isPsRest">
      <div v-if="!isReset" class="text-center">
        <app-typography fontWeight="600" variant="h1">{{ mainTitle }}</app-typography>
        <app-typography class="description" color="#76848D" variant="body1">{{ desc }}</app-typography>
      </div>
      <div v-if="isReset" class="d-flex flex-column text-center">
        <div class="text-center">
          <app-typography fontWeight="600" variant="h1">{{ resetTitle }}</app-typography>
          <app-typography class="description" color="#76848D" variant="body1">{{ resetMessage }}</app-typography>
        </div>
        <app-typography class="my-3" color="#76848D" variant="h6">Click below to login</app-typography>
        <reuseable-button width="100" @click="handleGotodashboard">
          Go to Dashboard
        </reuseable-button>
      </div>
      <form v-else class="my-3 fields-wrapper" @submit.prevent="onSubmit">
        <div class="row d-flex justify-content-center gap-3 align-items-center">
          <div class="col-sm-12 mb-2">
            <form-group-input
                v-model="user.password"
                :value="user.password"
                :error="passwordError"
                :height="16" :validation-rules="passwordValidationRules"
                :width="20" image-source="eye" label="Password" placeholder="Enter password"
                type="password"
                @blur="validateInput('password')">
            </form-group-input>
          </div>
          <div class="col-sm-12 mb-2">
            <form-group-input
                v-model="user.confirmPassword"
                :value="user.confirmPassword"
                :error="confirmPasswordError"
                :height="16"
                :validation-rules="confirmPasswordValidationRules"
                :width="20"
                image-source="eye"
                label="Confirm Password"
                placeholder="Enter confirm password"
                type="password"
                @blur="validateInput('confirmPassword')">
            </form-group-input>
          </div>
          <!-- Buttons wrapper -->
          <div class="col-sm-12">
            <reuseable-button :loading="loading" width="100" @click="onSubmit">
              {{ loading ? 'Loading...' : 'Reset Password' }}
            </reuseable-button>
            <back-button :startIcon="true" btnText="Back" class="my-3" @click="handleBack"/>

          </div>
        </div>
      </form>
      </div>
      <div v-else>
      <success-alert :title='resetTitle' @invite="backToLogin" :desc='resetMessage' btnText="Back to login" :isBackButton='false' />
      </div>
    </div>
  </div>
</template>

<script>
import {FormGroupInput, ReuseableButton, BackButton, AppTypography, LogoHeader} from "@/components/index.js";
import apis from "@/services/api.js";
import SuccessAlert from "@/components/SuccessAlert/successAlert.vue";
export default {
  name: "ChoosePassword",
  components: {
    FormGroupInput,
    AppTypography,
    LogoHeader,
    ReuseableButton,
    BackButton,
    SuccessAlert,
  },
  props: {
    errors: {
      type: Object,
      default: () => ({})
    },
    success:String,
  },
  data() {
    return {
      loading: false,
      isPsRest: false,
      mainTitle: "Choose password",
      desc: "Your new password must be different from previously used passwords.",
      isReset: false,
      resetTitle: "Password reset",
      resetMessage: "Your Password has been successfully reset",

      user: this.$inertia.form({
        password: "",
        email: '',
        otp: '',
        confirmPassword: "",

      }),
      passwordError: "",
      confirmPasswordError: "",
      passwordValidationRules: [
        {
          regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/,
          message: 'Password must be at least 8 characters long and contain uppercase, lowercase letters, and at least one special character.'
        }
      ],
      confirmPasswordValidationRules: [
        {
          validator: (value) => value === this.user.password,
          message: 'Passwords do not match.'
        }
      ],
    };
  },
  mounted() {
    if (this.success) {
      console.log(this.success);
    }

  },
  watch:{
    success(newVal, oldVal) {
      if (newVal !== oldVal) {
        console.log('New success message:', newVal);
        this.isPsRest = true
      }
    },

  },
  methods: {
    onSubmit() {
      this.passwordError = this.validateField(this.user.password, this.passwordValidationRules);
      this.confirmPasswordError = this.validateField(this.user.confirmPassword, this.confirmPasswordValidationRules);
      const isValid = !this.passwordError && !this.confirmPasswordError


      if (isValid) {
        this.loading = true;
        this.user.otp = this.$store.getters.getOtp;
        this.user.email = this.$store.getters.getEmail;
            // this.user.password = this.user.password
        this.user.post('/choose-password', {}, {
          onFinish: () => {
            this.loading = false;
          },
          onError: (error) => {
            this.loading = false;
            this.$toast('Failed to reset password. Please try again.', 'error');
            console.log("Error during password reset:", error);
          },
          onSuccess: (response) => {
            this.isPsRest = true
            console.log("Password reset successful", response);
            if (response.props.success) {

              this.isPsRest = true
            }
          }
        });
      } else {
        this.loading = false;
        console.log("Validation errors:", {

          passwordError: this.passwordError,
          confirmPasswordError: this.confirmPasswordError,

        });
      }

    },
    handleBack() {
      this.$inertia.visit('/forgot-password');
    },
    backToLogin() {
      this.$inertia.visit('/login');
    },
    handleGotodashboard() {
      this.$router.push('/');
    },
    validateInput(inputField) {
      switch (inputField) {

        case 'password':
          return this.passwordError = this.validateField(this.user.password, this.passwordValidationRules);
        case 'confirmPassword':
          return this.confirmPasswordError = this.validateField(this.user.confirmPassword, this.confirmPasswordValidationRules);

      }
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
      return '';
    }
  },
};
</script>

<style scoped>
.form-wrapper {
  padding: 0px 24px;
  max-width: 600px;
  margin: 81px auto;
}

.description {
  margin: 16px 0 32px 0;
}

.fields-wrapper {
  max-width: 481px;
  margin: auto;
}
</style>
