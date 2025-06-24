<template>
  <form @submit.prevent="onSubmit" class="my-4">
    <div class="row gy-4">
      <div class="col-md-12">
        <form-group-input
            type="text"
            label="Account Name"
            v-model="bankDetailsForm.account_name"
            :value="bankDetailsForm.account_name"
            :error="errors.account_name"
            @blur="validateInput('account_name')"
            maxlength="50"
        />
      </div>
      <div class="col-md-12">
        <form-group-input
            type="text"
            label="Card Number"
            v-model="bankDetailsForm.card_number"
            :value="bankDetailsForm.card_number"
            :error="errors.card_number"
            v-mask="'#### #### #### ####'"
            @blur="validateInput('card_number')"
        />
      </div>
      <div class="col-md-6">
        <form-group-input
            type="text"
            label="Expiry Date"
            v-model="bankDetailsForm.expiry"
            :value="bankDetailsForm.expiry"
            :error="errors.expiry"
            v-mask="'##/##'"
            @blur="validateInput('expiry')"
        />
      </div>
      <div class="col-md-6">
        <form-group-input
            type="text"
            label="Security Code (CVV)"
            v-model="bankDetailsForm.cvv"
            :value="bankDetailsForm.cvv"
            :error="errors.cvv"
            v-mask="'####'"
            @blur="validateInput('cvv')"
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
        type: "credit_card",
        account_name: "",
        card_number: "",
        expiry: "",
        cvv: ""
      }),
      errors: {
        account_name: '',
        card_number: '',
        expiry: '',
        cvv: ''
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
        card_number: [
          {
            validator: (value) => value && value.replace(/\s+/g, '').length === 16,
            message: 'Card Number must be exactly 16 digits.',
          },
          {
            regex: /^\d{4}\s?\d{4}\s?\d{4}\s?\d{4}$/,
            message: 'Card Number must be in valid format (16 digits).'
          }
        ],
        expiry: [
          {
            regex: /^(0[1-9]|1[0-2])\/([0-9]{2})$/,
            message: 'Expiry date must be in MM/YY format.'
          },
          {
            validator: (value) => {
              const [month, year] = value.split('/');
              const currentYear = new Date().getFullYear() % 100; // Get last two digits of the current year
              const currentMonth = new Date().getMonth() + 1; // Months are zero-based
              return (
                parseInt(year, 10) > currentYear || 
                (parseInt(year, 10) === currentYear && parseInt(month, 10) >= currentMonth)
              );
            },
            message: 'Card expiry date cannot be in the past.'
          }
        ],
        cvv: [
          {
            validator: (value) => value.trim().length > 0, // Ensure the field is not empty
            message: 'CVV is required.',
          },
          {
            regex: /^\d{3,4}$/, // 3 or 4 digits
            message: 'CVV must be 3 or 4 digits long.',
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

      // Perform validation
      const isValid = this.validateForm();

      if (isValid) {
        this.loading = true;
        this.bankDetailsForm.post('/store-payment-method', {
          preserveState: true,
          preserveScroll: true,
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
            // this.$toast('An error occurred while submitting', 'error');
            this.loading = false;
          }
        });
      }
    },
    cleanInput() {
      if (this.bankDetailsForm.card_number)
        this.bankDetailsForm.card_number = this.bankDetailsForm.card_number.replace(/\s+/g, '');
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
      return isValid;
    },
    validateField(value, rules) {
      if (rules) {
        for (const rule of rules) {
          // Card number ke liye spaces hata kar validate karein
          let val = value;
          if (Array.isArray(rules) && rules === this.validationRules.card_number) {
            val = value ? value.replace(/\s+/g, '') : '';
          }
          // Custom validator
          if (rule.validator && !rule.validator(val)) {
            return rule.message;
          }
          // Regex validator
          if (rule.regex && !rule.regex.test(val)) {
            return rule.message;
          }
        }
      }
      return '';
    },
    validateInput(field) {
      this.errors[field] = this.validateField(this.bankDetailsForm[field], this.validationRules[field]);
    },
  }
};
</script>

<style scoped>
.custom-stripe-input {
  border: 1px solid #dee2e6;
  padding: 20px;
  border-radius: 46px;
  margin-top: 8px;
}
</style>
