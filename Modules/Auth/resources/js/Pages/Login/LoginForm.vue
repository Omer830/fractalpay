<template>
  <form class="my-4" @submit.prevent="onSubmit">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="email"
            label="Email address"
            placeholder="Enter email"
            image-source="mail"
            :width="24"
            :height="24"
            v-model="user.email"
            :value="user.email"
            :validation-rules="emailValidationRules"
            :error="user.errors.email || emailError"
            @blur="validateInput('email')"
        />
      </div>
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="password"
            label="Password"
            placeholder="Enter password"
            image-source="eye"
            :width="20"
            :height="16"
            v-model="user.password"
            :value="user.password"
            :validation-rules="passwordValidationRules"
            :error="passwordError"
            @blur="validateInput('password')"
        />
      </div>
      <div class="col-sm-12 mb-3 d-flex justify-content-end">
        <app-typography variant="body1">
          <Link href="/forgot-password" class="forgot-password">Forgot Password?</Link>
        </app-typography>
      </div>
      <!-- buttons wrapper  -->
      <div class="col-sm-12 d-flex flex-column gap-3 align-items-center mt-2">
        <reuseable-button width="100" @click="onSubmit" :disabled="loading">
          <span v-if="loading">Logging in...</span>
          <span v-else>Login</span>
        </reuseable-button>
        <div class="w-75 d-flex align-items-center my-4">
          <!-- Left divider -->
          <div class="divider"></div>
          <app-typography class="divider-text mb-4" variant="h6" color="#76848D">OR</app-typography>
          <!-- Right divider -->
          <div class="divider"></div>
        </div>
        <reuseable-button outline width="75" textColor="#141519" :imageWidth="24" :imageHeight="24" startIcon="google" round="lg">
          Login via Google
        </reuseable-button>
        <reuseable-button width="75" :imageWidth="18" :imageHeight="18" startIcon="facebook" round="lg">
          Log In via Facebook
        </reuseable-button>
        <app-typography class="my-4" variant="body1" fontWeight="500" color="#76848D">{{ registerText }}
          <Link style="color: #2A52BE" href="/signup">{{ registerLink }}</Link>
        </app-typography>
      </div>
    </div>
  </form>
</template>
<script>
import { AppTypography, FormGroupInput, ReuseableButton } from "@/components/index";
import { useForm } from '@inertiajs/inertia-vue3';
import apis from "@/services/api";
import {reactive} from "vue";
import { Link } from '@inertiajs/vue3';

export default {
  name: "LoginForm",
  components: {
    AppTypography,
    FormGroupInput,
    ReuseableButton,
    Link
  },
  props: {
    errors: Object
  },
  data() {
    return {
      registerText: "Enter your credentials to access your account",
      registerLink: "Register here",
      user: this.$inertia.form({
        email: '',
        password: '',
        remember: false,
      }),
      emailValidationRules: [
        {
          regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
          message: 'Please enter a valid email address.'
        }
      ],
      passwordValidationRules: [
        {
          regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/,
          message: 'Password must be at least 8 characters long and contain uppercase, lowercase letters, and at least one special character.'
        }
      ],
      emailError: '',
      passwordError: '',
      loading: false,
      FormData:{},
      testingModal: false,
    };
  },
  methods: {
    onSubmit() {
      // Always submit email as lowercase
      if (this.user.email) {
        this.user.email = this.user.email.toLowerCase();
      }

      const isEmailValid = !this.validateInput('email');
      const isPasswordValid = !this.validateInput('password');

      if (isEmailValid && isPasswordValid) {
        this.$store.commit('SET_EMAIL', this.user.email);
        this.loading = true;
        this.user.post('/login', {
          onSuccess: (response) => {
        
            this.$toast('Login successful', 'success');
          },
          onFinish: () => {
            // this.loading = false;
          },
          onError: (error) => {
            this.$toast(`Error: ${error.message || 'An error occurred'}`, 'error');
            console.log('error hit' ,error)
            this.loading = false;
          }
        });
      }
    },


    validateInput(inputField) {
      switch (inputField) {
        case 'email':
          return this.emailError = this.validateField(this.user.email, this.emailValidationRules);

        case 'password':
          return this.passwordError = this.validateField(this.user.password, this.passwordValidationRules);
      }
    },
    validateField(value, rules) {
      for (const rule of rules) {
        if (!rule.regex.test(value)) {
          return rule.message;
        }
      }
      return ''; // Empty string indicates no error
    }
  }
};
</script>
<style scoped>
.forgot-password {
  text-decoration: none;
  color: #4682BE
}

.divider {
  border: 1px solid #d0d3e8;
  width: 100%;
  margin: 0 15px;
}
</style>
