<template>
  <form @submit.prevent="onSubmit">
    <div class="row d-flex justify-content-center gap-3 align-items-center">
      <div class="col-sm-12 mb-2">
        <form-group-input
            v-model="user.email"
            :value="user.email"
            :error="user.errors.email || emailError"
            :height="24"
            :validation-rules="emailValidationRules"
            :width="24"
            image-source="mail"
            label="Email Address"
            placeholder="Enter email"
            type="email"
            @blur="validateInput('email')"
        />
      </div>
      <!-- Buttons wrapper -->
      <div class="col-sm-12 d-flex flex-column gap-3 align-items-center">
        <reuseable-button
            :disabled="loading"
            width="100"
            @click="onSubmit"
        >
          <span v-if="loading">Sending...</span>
          <span v-else>Send Email</span>
        </reuseable-button>
        <back-button
            :startIcon="true"
            btnText="Back to log in"
            @click="handleBackToLogin"
        />
      </div>
    </div>
  </form>
</template>


<script>
import {FormGroupInput, ReuseableButton, BackButton} from "@/components/index.js";
import apis from "@/services/api.js";
import {router} from '@inertiajs/vue3'

export default {
  name: "ForgotPasswordForm",
  components: {
    FormGroupInput,
    ReuseableButton,
    BackButton,
  },
  props: {
    errors: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      loading: false,
      user: this.$inertia.form({
        email: '',
      }),
      emailError: '',
      emailValidationRules: [
        {
          regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
          message: 'Please enter a valid email address.'
        }
      ],
    };
  },
  watch: {
    'user.errors': {
      deep: true,
      handler(newVal) {
        this.loading = false;
      }
    }
  },
  methods: {
    onSubmit() {
      this.emailError = this.validateField(this.user.email, this.emailValidationRules);

      // If no validation errors, proceed with form submission
      if (!this.emailError) {
        this.loading = true; // Set loading to true before the form submission
        this.$store.commit('SET_EMAIL',this.user.email)
        this.user.post('/sendOTP', {
          onFinish: () => {
            // this.loading = false;
          },
          onError: () => {
            this.loading = false;
          },
          onSuccess: (response) => {
            this.$inertia.visit('/forgot-password/otp');
            this.loading = false;
            if (response.props.success) {
              this.$inertia.visit('/forgot-password/otp'); // Redirect to OTP page
            }
          }
        });
      }
    },

    handleBackToLogin() {
      this.$inertia.visit('/login');
    },

    validateInput(inputField) {
      if (inputField === 'email') {
        this.emailError = this.validateField(this.user.email, this.emailValidationRules);
      }
    },

    validateField(value, rules) {
      if (!rules) return ''; // If rules are undefined, return an empty string
      for (const rule of rules) {
        if (rule.regex && !rule.regex.test(value)) {
          return rule.message;
        }
        if (rule.validator && !rule.validator(value)) {
          return rule.message;
        }
      }
      return '';
    }
  },
};
</script>



<style scoped></style>
