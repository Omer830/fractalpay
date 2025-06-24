<template>
  <form @submit.prevent="onSubmit" class="my-4">
    <div class="row gy-4">
      <div class="col-md-6">
        <form-group-input
            type="text"
            label="Account Name"
            v-model="bankDetailsForm.account_name"
            :value="bankDetailsForm.account_name"
            :error="errors.account_name"
            @blur="validateInput('account_name')"
            :maxlength="50"
        />
      </div>
      <div class="col-md-6">
        <form-group-input
            type="text"
            label="Account Number"
            v-model="bankDetailsForm.account_number"
            :value="bankDetailsForm.account_number"
            :error="errors.account_number"
            @blur="validateInput('account_number')"
            :maxlength="30"
        />
      </div>
      <div class="col-md-4">
        <form-group-input
            type="text"
            label="Bank Name"
            v-model="bankDetailsForm.bank_name"
            :value="bankDetailsForm.bank_name"
            :error="errors.bank_name"
            @blur="validateInput('bank_name')"
            :maxlength="50"
        />
      </div>
      <div class="col-md-4">
        <form-group-input
            type="text"
            label="Bank BSB"
            v-model="bankDetailsForm.bsb"
            :value="bankDetailsForm.bsb"
            :error="errors.bsb"
            v-mask="'###-###'"
            @blur="validateInput('bsb')"
            :maxlength="16"
        />
      </div>
      <div class="col-md-4">
        <form-group-input
            type="text"
            label="Swift Code (Optional)"
            v-model="bankDetailsForm.swiftCode"
            :value="bankDetailsForm.swiftCode"
            :maxlength="11"
        />
      </div>
    </div>
    <div class="d-flex justify-content-end mt-4">
      <reuseable-button type="submit" class="px-5" :disabled="loading">
        <span v-if="loading">Submitting...</span>
        <span v-else>Submit</span>
      </reuseable-button>
    </div>
  </form>
</template>

<script>
import { FormGroupInput, ReuseableButton } from "@/components/index";
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    ReuseableButton,
    FormGroupInput
  },
  data() {
    return {
      bankDetailsForm: this.$inertia.form({
        account_name: '',
        account_number: '',
        bsb: '',
        swiftCode: '',
        bank_name: '',
        type: "bank_account",
      }),
      errors: {
        account_name: '',
        account_number: '',
        bsb: '',
        swiftCode: '',
        bank_name: ''
      },
      validationRules: {
        account_name: [
          {
            validator: (value) => value.trim().length > 0, // Ensure the field is not empty
            message: 'Account Name is required.',
          },
          {
            validator: (value) => value.trim().length >= 3, // Ensure at least 3 non-space characters
            message: 'Account Name must be at least 3 characters long.',
          },
          {
            regex: /^[a-zA-Z\s]+$/, // Allow only letters and spaces
            message: 'Account Name must contain only letters and spaces.',
          },
        ],
        bank_name: [
          {
            validator: (value) => value.trim().length > 0, // Ensure the field is not empty
            message: 'Bank Name is required.',
          },
          {
            regex: /^.{2,}$/, // Minimum 2 characters for bank name
            message: 'Bank Name must be at least 2 characters long.',
          },
        ],
      },
      loading: false,

    };
  },
  methods: {
    onSubmit() {
      // Clean the input before validating
      this.cleanInput();

      // Convert string inputs to numbers
      this.convertInputsToNumbers();

      // Perform validation
      const isValid = this.validateForm();

      if (isValid) {
        this.loading = true;
        this.bankDetailsForm.post('/store-payment-method', {
          onSuccess: () => {
            this.$toast('Payment Method submitted successfully', 'success');
            // this.$inertia.visit('/payment-method')
            window.history.back()
          },
          onFinish: () => {
            this.loading = false;
          },
          onError: (errors) => {
            this.errors = errors;
            this.$toast('An error occurred while submitting', 'error');
            this.loading = false;
          }
        });
      }
    },
    cleanInput() {
      // Remove spaces from account number
      if (this.bankDetailsForm.account_number) {
        this.bankDetailsForm.account_number = this.bankDetailsForm.account_number
          .toString() // Convert to string
          .replace(/\s+/g, ''); // Remove spaces
      }

      // Remove non-numeric characters from BSB
      if (this.bankDetailsForm.bsb) {
        this.bankDetailsForm.bsb = this.bankDetailsForm.bsb
          .toString() // Convert to string
          .replace(/[^0-9]/g, ''); // Remove non-numeric characters
      }
    },
    convertInputsToNumbers() {
      // Convert account number to a number only if it's valid
      if (this.bankDetailsForm.account_number && !isNaN(this.bankDetailsForm.account_number)) {
        this.bankDetailsForm.account_number = Number(this.bankDetailsForm.account_number);
      }
    },
    validateForm() {
      let isValid = true;

      Object.keys(this.validationRules).forEach(field => {
        const error = this.validateField(this.bankDetailsForm[field], this.validationRules[field]);
        if (error) {
          this.errors[field] = error;
          isValid = false;
        } else {
          this.errors[field] = '';
        }
      });

      // Check if the account number is empty
      if (!this.bankDetailsForm.account_number || this.bankDetailsForm.account_number.trim() === '') {
        this.errors.account_number = 'Account Number is required.';
        isValid = false;
      } else {
        this.errors.account_number = '';
      }

      // Check if BSB is exactly 6 digits after cleaning
      if (!this.bankDetailsForm.bsb || this.bankDetailsForm.bsb.toString().length !== 6) {
        this.errors.bsb = 'Bank BSB must be exactly 6 digits.';
        isValid = false;
      } else {
        this.errors.bsb = '';
      }

      return isValid;
    },
    validateField(value, rules) {
      if (rules) {
        for (const rule of rules) {
          // Check for custom validator first (e.g., empty field validation)
          if (rule.validator && !rule.validator(value)) {
            return rule.message; // Return error for empty field
          }
          // Check for regex validation (e.g., format validation)
          if (rule.regex && !rule.regex.test(value)) {
            return rule.message; // Return error for invalid format
          }
        }
      }
      return ''; // No error
    },
    validateInput(field) {
      if (field === 'bsb') {
        // Check if the field is empty
        if (!this.bankDetailsForm.bsb || this.bankDetailsForm.bsb.trim() === '') {
          this.errors.bsb = 'Bank BSB is required.';
          return;
        }

        // Check if the field has exactly 6 digits
        const cleanBSB = this.bankDetailsForm.bsb.toString().replace(/[^0-9]/g, '');
        if (cleanBSB.length !== 6) {
          this.errors.bsb = 'Bank BSB must be exactly 6 digits.';
        } else {
          this.errors.bsb = ''; // Clear error if validation passes
        }
      } else {
        // Default validation for other fields
        this.errors[field] = this.validateField(this.bankDetailsForm[field], this.validationRules[field]);
      }
    }
  }
};
</script>



