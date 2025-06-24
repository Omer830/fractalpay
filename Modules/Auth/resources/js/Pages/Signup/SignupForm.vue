<template>
  <form @submit.prevent="onSubmit">
    <div class="row gy-2">
      <!-- First Name -->
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="text"
            label="First Name"
            placeholder="Enter first name"
            v-model="user.first_name"
            :validation-rules="firstNameValidationRules"
            image-source="user"
            :height="24"
            :width="25"
            :value="user.first_name"
            :error="user.errors.first_name || firstNameError"
            @blur="validateInput('firstName')"
            :required="true"
            maxlength="50" 
        >
        </form-group-input>
      </div>

      <!-- Last Name -->
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="text"
            label="Last Name"
            placeholder="Enter last name"
            v-model="user.last_name"
            :validation-rules="lastNameValidationRules"
            image-source="user"
            :height="24"
            :width="25"
            :error="user.errors.last_name || lastNameError"
            :required="true"
            @blur="validateInput('lastName')"
            :value="user.last_name"
            maxlength="50" 
        >
        </form-group-input>
      </div>

      <!-- Email -->
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="email"
            label="Email"
            placeholder="Enter email"
            v-model="user.email"
            :validation-rules="emailValidationRules"
            :value="user.email"
            image-source="mail"
            :width="24"
            :height="24"
            :error="user.errors.email || emailError"
            :required="true"
            @blur="validateInput('email')"
            maxlength="100" 
        >
        </form-group-input>
      </div>

      <!-- Password -->
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="password"
            label="Password"
            placeholder="Enter password"
            v-model="user.password"
            :validation-rules="passwordValidationRules"
            :value="user.password"
            image-source="eye"
            :width="20"
            :height="16"
            :error="user.errors.password || passwordError"
            :required="true"
            @blur="validateInput('password')"
            maxlength="50"
        >
        </form-group-input>
      </div>

      <!-- Referral Code -->
      <div class="col-sm-12 mb-2">
        <form-group-input
            type="text"
            label="Referral Code"
            placeholder="Enter referral code"
            v-model="user.referee_code"
            :validation-rules="refereeCodeValidationRules"
            :value="user.referee_code"
            image-source="invitation-icon"
            :width="24"
            :height="24"
            :error="user.errors.referee_code || refereeCodeError"
            maxlength="6" 
        >
        </form-group-input>
        <div class="flex d-flex justify-content-between items-center" v-if="user.referee_code">
          <app-typography
              v-if="user.referee_code"
              class="cursor-not-allowed mt-2"
              variant="h7" color="#4682BE">You've been invited by {{ this.userData.user_name }} user!</app-typography>
          <app-typography

              class="cursor-pointer mt-2 text-end"
              @click="rejectInvitation(user.referee_code)"
              variant="h7" color="#4682BE">Remove Code</app-typography>
        </div>
      </div>
      <div class="col-sm-12 mb-2">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
               v-model="user.termsAndCondition" />
        <label class="form-check-label" for="flexCheckDefault">
          <app-typography variant="h6" color="#4682BE">
            <!-- Using inertia-link for SPA navigation -->
            <Link class="link" href="/terms-and-conditions">{{ termsText }}</Link>
          </app-typography>
        </label>
      </div>
      <div class="col-sm-12 mt-3 d-flex flex-column align-items-center">
        <reuseable-button :disabled="!user.termsAndCondition || loading" @click="onSubmit" width="100">
          <span v-if="loading">Signing Up...</span>
          <span v-else>Sign Up</span>
        </reuseable-button>
        <app-typography class="my-4" variant="body1" fontWeight="500" color="#76848D">Already have an account?
          <Link style="color: #2A52BE" href="/login">Log In</Link >
        </app-typography>
      </div>
    </div>
  </form>
</template>


<script>
import { FormGroupInput, AppTypography, ReuseableButton } from "@components/index";
import {Link, usePage} from '@inertiajs/vue3';
import Swal from "sweetalert2";
const userData = usePage();
export default {
  name: "SignupForm",
  components: {
    FormGroupInput,
    AppTypography,
    ReuseableButton,
    Link
  },
  props: {
    errors: Object
  },
  data() {
    return {
      user: this.$inertia.form({
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        termsAndCondition: false,
        referee_code: null,
      }),
      termsText: "Terms & Conditions",
      firstNameValidationRules: [
        {
          regex: /^[a-zA-Z\s]+$/, // Allows only alphabets and spaces
          message: 'No numbers or special characters are allowed in the name.'
        }
      ],
      lastNameValidationRules: [
        {
          regex: /^[a-zA-Z\s]+$/, // Allows only alphabets and spaces
          message: 'No numbers or special characters are allowed in the name.'
        }
      ],
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
      refereeCodeValidationRules: [
        {
          regex: /^[a-zA-Z0-9]{0,6}$/, // Allows up to 6 alphanumeric characters
          message: 'Referral code must be alphanumeric and up to 6 characters long.'
        }
      ],
      firstNameError: '',
      lastNameError: '',
      emailError: '',
      passwordError: '',
      confirmPasswordError: '',
      termsError: '',
      refereeCodeError: '',
      loading: false,
      userData: userData.props,
    };
  },
  mounted() {
    this.user.referee_code = this.userData.referee_code
  },
  watch: {
    'user.errors': {
      deep: true,
      immediate: true,
      handler(newVal, oldVal) {
        if (Object.keys(newVal).length > 0) {
          this.loading = false;
        }
      }
    }
  },
  methods: {
    onSubmit() {
      // Convert email to lowercase before validation and submit
      if (this.user.email) {
        this.user.email = this.user.email.toLowerCase();
      }

      this.firstNameError = this.validateField(this.user.first_name, this.firstNameValidationRules);
      this.lastNameError = this.validateField(this.user.last_name, this.lastNameValidationRules);
      this.emailError = this.validateField(this.user.email, this.emailValidationRules);
      this.passwordError = this.validateField(this.user.password, this.passwordValidationRules);
      this.refereeCodeError = this.validateField(this.user.referee_code, this.refereeCodeValidationRules);

      const isValid = !this.firstNameError && !this.lastNameError && !this.emailError &&
          !this.passwordError && !this.refereeCodeError && this.user.termsAndCondition;

      if (isValid) {
        this.loading = true;
        this.$store.commit('SET_EMAIL', this.user.email);

        this.user.post('/signup', {}, {
          onFinish: () => {
            this.loading = false;
          },
          onError: (errors) => {
            console.log('error hit', errors);
            this.$toast(`Error: ${errors.message || 'An error occurred'}`, 'error');
          },
          onSuccess: (response) => {
            if (response.props.success) {
              this.$toast('Signup successful', 'success');
            }
          }
        });
      } else {
        this.loading = false;
      }
    },

    validateInput(inputField) {
      switch (inputField) {
        case 'firstName':
          return this.firstNameError = this.validateField(this.user.first_name, this.firstNameValidationRules);
        case 'lastName':
          return this.lastNameError = this.validateField(this.user.last_name, this.lastNameValidationRules);
        case 'email':
          return this.emailError = this.validateField(this.user.email, this.emailValidationRules);
        case 'password':
          return this.passwordError = this.validateField(this.user.password, this.passwordValidationRules);
        case 'refereeCode':
          return this.refereeCodeError = this.validateField(this.user.referee_code, this.refereeCodeValidationRules);
      }
    },

    validateField(value, rules) {
      if (!rules) return ''; // If rules are undefined, return empty string

      for (const rule of rules) {
        if (rule.regex && !rule.regex.test(value)) {
          return rule.message;
        }
        if (rule.validator && !rule.validator(value)) {
          return rule.message;
        }
      }
      return '';
    },

    rejectInvitation(code) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4682BE',
        cancelButtonColor: '#bcbcbc',
        confirmButtonText: 'Yes, reject it!'
      }).then((result) => {
        if (result.isConfirmed) {
          // this.loading = true;
          this.user.referee_code = null;
        }
      });
    }
  }
};
</script>


<style scoped>

.link {
  color: #4682BE;  /* Example color */
  text-decoration: none;
  margin-left: 5px;
}
</style>
