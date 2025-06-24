<template>
  <div class="container">
    <div class="card">


      <div class="form-wrapper">
        <app-typography variant="h4" fontWeight="500" class="my-4">
          {{ mainTitle }}
        </app-typography>
        <form @submit.prevent="handleSubmit">
          <div class="row gy-3">
<!--                        <div class="col-md-6">-->
<!--                          <form-group-input-->
<!--                              type="text"-->
<!--                              label="First Name"-->
<!--                              placeholder="Enter name"-->
<!--                              :value="formData.first_name"-->
<!--                              v-model="formData.first_name"-->
<!--                              borderRadiusClass="border-sm"-->
<!--                              image-source="user"-->
<!--                              :height="24"-->
<!--                              :width="25"-->
<!--                              :error="errors.first_name || first_nameError"-->
<!--                              @blur="validateInput('name')"-->
<!--                              :required="true"-->
<!--                          />-->
<!--                        </div>-->
<!--            <div class="col-md-6">-->
<!--              <form-group-input-->
<!--                  type="text"-->
<!--                  label="Last Name"-->
<!--                  placeholder="Enter your last name"-->
<!--                  :value="formData.last_name"-->
<!--                  v-model="formData.last_name"-->
<!--                  borderRadiusClass="border-sm"-->
<!--                  image-source="user"-->
<!--                  :height="25"-->
<!--                  :width="25"-->
<!--                  :error="errors.last_name || last_nameError"-->
<!--                  @blur="validateInput('address')"-->
<!--                  :required="true"-->
<!--                  :readOnly="false"-->
<!--              />-->
<!--            </div>-->
            <div class="col-md-6">

              <form-group-input
                  type="email"
                  label="Email"
                  placeholder="Enter your email"
                  :value="formData.email"
                  v-model="formData.email"
                  borderRadiusClass="border-sm"
                  image-source="email"
                  :height="25"
                  :width="25"
                  :error="errors.email || emailError"
                  @blur="validateInput('address')"
                  :required="true"
                  :readOnly="true"

              />

            </div>
            <div class="col-md-6">

              <form-group-input
                  type="number"
                  label="Mobile/Cell"
                  placeholder="Enter your email"
                  :value="formData.phone_number"
                  v-model="formData.phone_number"
                  borderRadiusClass="border-sm"
                  image-source="email"
                  :height="25"
                  :width="25"
                  :error="errors.phone_number || emailError"
                  @blur="validateInput('address')"
                  :required="true"

              />
            </div>
            <div class="col-md-6">
              <form-group-input
                  type="email"
                  label="Secondary Email"
                  placeholder="Enter your email"
                  :value="formData.secondary_email"
                  v-model="formData.secondary_email"
                  borderRadiusClass="border-sm"
                  image-source="email"
                  :height="25"
                  :width="25"
                  :error="errors.secondary_email || secondary_emailError"
                  @blur="validateInput('address')"
                  :required="true"

              />
              <div>
              <app-typography
                  v-if="!formData?.secondary_email_verified"
                  @click="handleEmailVerification(formData.secondary_email)"
                  fontWeight="100"
                  class="my-0 text-26 text-end cursor-pointer" fontColor="#4682BE">
                Verify Email?
              </app-typography>
              </div>
            </div>
            <div class="col-md-6">

              <form-group-input
                  type="number"
                  label="Landline/home"
                  placeholder="Enter your Number"
                  :value="formData.phone_number"
                  v-model="formData.phone_number"
                  borderRadiusClass="border-sm"
                  image-source="email"
                  :height="25"
                  :width="25"
                  :error="errors.phone_number || emailError"
                  @blur="validateInput('address')"
                  :required="true"

              />
            </div>



          </div>
        </form>

      </div>
    </div>
  </div>
  <EmailVerification title="Email Verification"
                     :form-data="emailFormData"
                     v-model:is-visible="emailModalStatus"
                     :is-visible="emailModalStatus"
  />
  <common-footer @back="goBackHandler" :isBackBtn="false" buttonTitle="Submit" @continue="updateEmailAttribute"/>
</template>

<script>
import {AppTypography, FormGroupInput, ReuseableButton} from "@/components/index";
import PROFILE_AVATAR from "../../assets/images/owner-profile.png";
import MALE_AVATAR from "../../assets/images/Male.png";
import FEMALE_AVATAR from "../../assets/images/Female.png";
import CommonFooter from "../CommonFooter/commonFooter.vue";
import AppSelect from "../Select/appSelect.vue";
import {usePage} from "@inertiajs/vue3";
import axios from "axios";
import api from "@services/api.js";
import EmailVerification from "@components/Modal/EmailVerification.vue";

const userData = usePage();
export default {
  components: {
    EmailVerification,
    AppTypography,
    FormGroupInput,
    ReuseableButton,
    CommonFooter,
    AppSelect
  },
  props: {
    avatar: {
      type: String,
      default: PROFILE_AVATAR,
    },
    userName: {
      type: String,
      default: "User Name",
    },
    userStatus: {
      type: String,
      default: "offline",
    },
    isVerificationBtn: {
      type: Boolean,
      default: false
    },

    errors: {
      type: Object,
      default: () => ({})
    },
    userProfile: {
      type: Object,
      default: () => ({})
    },
    countries: {
      type: Array,
      default: () => {
        return []
      }
    },
    cities: {
      type: Array,
      default: () => {
        return []
      }
    },
    states: {
      type: Array,
      default: () => {
        return []
      }
    }
  },
  emits: ['form-submitted', 'back-btn', 'continue-btn', 'verify'],
  data() {
    return {
      userData: userData.props.auth.user,
      selectedFile: null,
      avatar: this.avatar,
      mainTitle: "Account",
      profile: this.$inertia.form({
        file: null,
        file_type: 'profile-image',
      }),
      emailModalStatus:false,
      emailFormData:{},
      formData: {
        first_name: "",
        last_name:"",
        address: "",
        email:"",
        secondary_email:"",
        phone_number:"",
        landline_number:'',

      },

      user: this.$inertia.form({
        email: '',
      }),
      first_nameError: "",
      last_nameError: "",
      addressError: "",
      postalCodeError: "",
      cityError: "",
      stateError: "",
      countryError: "",
      genderError: "",
      dobError: "",
      userNameError: "",
      emailError:"",
      secondary_emailError:"",
      alternative_emailError:"",
      // Define validation rules for each input field
      nameValidationRules: [
        {
          regex: /^[a-zA-Z\s]+$/,
          message: 'Please enter a valid name.'
        }
      ],
      addressValidationRules: [
        {
          validator: (value) => {
            // Ensure the address is not empty
            return value.trim().length > 0;
          },
          message: 'Address is required.',
        },
        {
          validator: (value) => {
            // Validate the format of the address
            const regex = /^[a-zA-Z0-9\s,'./#-]*$/;
            return regex.test(value);
          },
          message: 'Please enter a valid address.',
        }
      ],
      postalCodeValidationRules: [
        {
          regex: /^[A-Za-z0-9\s-]{3,10}$/,
          message: 'Please enter a valid postal code.'
        }
      ],
      cityValidationRules: [
        {
          validator: (value) => {
            return value !== null && value !== undefined && value !== '';
          },
          message: 'Please select a city.',
        },
      ],
      stateValidationRules: [
        {
          validator: (value) => {
            return value !== null && value !== undefined && value !== '';
          },
          message: 'Please select a State.',
        },
      ],
      countryValidationRules: [
        {
          validator: (value) => {
            return value !== null && value !== undefined && value !== '';
          },
          message: 'Please select a Country.',
        },
      ],
      genderValidationRules: [
        {
          validator: (value) => {
            return value !== null && value !== undefined && value !== '';
          },
          message: 'Please select a Gender.',
        },
      ],
      dobValidationRules: [
        {
          validator: (value) => {
            // Ensure DOB is not null, undefined, or empty
            return value && value.length > 0;
          },
          message: 'Date of birth is required.',
        },
      ],
      userNameValidationRules: [
        {
          regex: /^[a-zA-Z0-9_]+$/,
          message: 'Username is invalid.',
        },
      ],
    };
  },
  mounted() {
    this.initializeFormData();
  },
  watch: {
    'formData.country_id': function (newVal) {
      const countrySlug = this.countries.find(country => country.id == newVal)?.slug;
      if (countrySlug) {
        this.handleCountryChange(countrySlug);
      }
    },
    'formData.state': function (newVal) {

      const stateSlug = this.states.find(state => state.slug == newVal)?.slug;
      if (stateSlug) {
        this.handleStateChange(stateSlug);
      }
    }
  },

  methods: {

    initializeFormData() {
      console.log(JSON.stringify(this.userProfile))
      if (this.userProfile) {
        this.formData.first_name = this.userProfile.data.first_name  || '';
        this.formData.last_name =  this.userProfile.data.last_name || '';
        this.formData.email =  this.userProfile.data.email || '';
        this.formData.secondary_email =  this.userProfile.data.secondary_email || '';
        this.formData.address = this.userProfile.data.address || '';
        this.formData.postal_code = this.userProfile.data.postal_code || '';
        this.formData.city = this.userProfile.data.city || '';
        this.formData.state = this.userProfile.data.state || '';
        this.formData.gender = this.userProfile.data.gender || '';
        this.formData.date_of_birth = this.userProfile.data.date_of_birth || '';
        this.formData.user_name = this.userProfile.data.user_name || '';
        // this.formData.country_id = this.userProfile.data.country || null;

        const userCountry = this.userProfile.data.country;
        if (userCountry) {
          const matchedCountry = this.countries.find(country => country.id == userCountry);
          this.formData.country_id = matchedCountry ? matchedCountry.id : null;
        }
        if (!this.userProfile.data.profile_image) {
          this.avatar = this.userProfile.data.gender === 'Male' ? MALE_AVATAR : FEMALE_AVATAR;
        } else {
          this.avatar = this.userProfile.data.profile_image;
        }
      }
    },

    isValidDate(dateStr) {
      const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
      return dateRegex.test(dateStr) && new Date(dateStr).toString() !== 'Invalid Date';
    },

    formatDate(dateStr) {
      if (!this.isValidDate(dateStr)) {
        const parts = dateStr.split('/');
        if (parts.length === 3) {
          return `${parts[2]}-${parts[0].padStart(2, '0')}-${parts[1].padStart(2, '0')}`;
        }
      }
      return dateStr; // Return as is if already valid or cannot be parsed
    },

    handleSubmit() {

      // Validate all fields before submission
      const isNameValid = this.validateInput("name");
      const isAddressValid = this.validateInput("address");
      const isPostalCodeValid = this.validateInput("postal_code");
      const isCityValid = this.validateInput("city");
      const isStateValid = this.validateInput("state");
      const isCountryValid = this.validateInput("country_id");
      const isGenderValid = this.validateInput("gender");
      const isDobValid = this.validateInput("date_of_birth");
      const isUserNameValid = this.validateInput("user_name");

      // If all fields are valid, proceed with submission
      if (this.formData.date_of_birth && !this.isValidDate(this.formData.date_of_birth)) {
        this.formData.date_of_birth = this.formatDate(this.formData.date_of_birth);
      }
      if (
          isNameValid.length === 0 &&
          isAddressValid.length === 0 &&
          isPostalCodeValid.length === 0 &&
          isCityValid.length === 0 &&
          isStateValid.length === 0 &&
          isCountryValid.length === 0 &&
          isGenderValid.length === 0 &&
          isDobValid.length === 0 &&
          isUserNameValid.length === 0
      ) {
        this.$emit("form-submitted", this.formData);
      }
    },
    validateInput(inputField) {
      switch (inputField) {
        case "name":
          return this.nameError = this.validateField(
              this.formData.name,
              this.nameValidationRules
          );
        case "address":
          return this.addressError = this.validateField(
              this.formData.address,
              this.addressValidationRules
          );
        case "postal_code":
          return this.postalCodeError = this.validateField(
              this.formData.postal_code,
              this.postalCodeValidationRules
          );
        case "city":
          return this.cityError = this.validateField(
              this.formData.city,
              this.cityValidationRules
          );
        case "state":
          return this.stateError = this.validateField(
              this.formData.state,
              this.stateValidationRules
          );
        case "country_id":
          return this.countryError = this.validateField(
              this.formData.country_id,
              this.countryValidationRules
          );
        case "gender":
          return this.genderError = this.validateField(
              this.formData.gender,
              this.genderValidationRules
          );
        case "date_of_birth":
          return this.dobError = this.validateField(
              this.formData.date_of_birth,
              this.dobValidationRules
          );
        case "user_name":
          return this.userNameError = this.validateField(
              this.formData.user_name,
              this.userNameValidationRules
          );
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
    },
    goBackHandler() {
      this.$emit('back-btn')
    },
    continueHandler() {
      this.$emit('continue-btn')
    },
    updateVarification() {
      this.$inertia.visit('doc-type')
    },
    hanldeVerify() {
      this.$emit('verify')
    },
    handleCountryChange(countrySlug) {
      this.$inertia.get('/edit-profile', {country: countrySlug}, {
        preserveState: true,
        only: ['states']
      });
    },
    handleStateChange(stateSlug) {
      this.$inertia.get('/edit-profile', {state: stateSlug}, {
        preserveState: true,
        only: ['cities']
      });
    },
    handleAvatarChange() {
      const fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.accept = 'image/jpeg,image/png,image/jpg,image/gif';

      fileInput.onchange = e => {
        const file = e.target.files[0];

        if (!file) return;

        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
          this.$toast('Invalid file type. Please upload a JPEG, PNG, JPG, or GIF image.', 'error');
          return;
        }

        this.selectedFile = file;
        this.profile.file = file;

        const reader = new FileReader();
        reader.onload = e => {
          const newImage = e.target.result;
          this.avatar = newImage; // Local component update
          this.$store.commit('SET_PROFILE_IMG', newImage);
          this.handleSubmitProfileImage();
        };
        reader.readAsDataURL(file);
      };

      fileInput.click();
    },


    handleSubmitProfileImage() {
      const formData = new FormData();

      formData.append('file', this.profile.file);
      formData.append('file_type', this.profile.file_type);

      this.profile.post('/edit-profile', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        onSuccess: () => {
          this.$toast('Profile image upload successful','success');
        },
        onError: () => {
          this.$toast('Upload failed. Please try again.', 'error');
        },
        forceFormData: true
      });
    },

    async updateEmailAttribute() {
      const email = this.formData.secondary_email;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      // Check if email is empty
      if (!email) {
        this.secondary_emailError = "Email is required.";
        return;
      }

      // Check if email format is valid
      if (!emailRegex.test(email)) {
        this.secondary_emailError = "Invalid email format.";
        return;
      }

      // Clear error if valid
      this.secondary_emailError = "";

      try {
        const payload = { secondary_email: email };
        const res = await api.updateEmailAttribute(payload);
        if (res) {
          console.log('Email attribute updated');
        }
      } catch (err) {
        console.error('Failed to update email:', err);
      }
    },

    async handleEmailVerification(email) {
      this.$store.commit('SET_EMAIL', email);

      this.user.email = email; // assuming `this.user` is an Inertia form object
      this.loading = true;
      this.emailModalStatus = !this.emailModalStatus;

      try {
        // Send all answers in a single request
        const response = await axios.post('/api/send_otp', this.user);
        this.$toast('Security questions submitted successfully', 'success');
        console.log('Security questions submitted successfully:', response.data);
        // this.$emit('form-submitted');
      } catch (error) {
        console.error('Error submitting security answers:', error.response?.data || error.message);
        this.$toast('Error submitting security answers', 'error');
      }

      // this.user.post('/sendOTP', {
      //   preserveScroll: true,
      //   onSuccess: () => {
      //     this.emailModalStatus = !this.emailModalStatus;
      //     this.loading = false;
      //     console.log('OTP sent successfully');
      //   },
      //   onError: () => {
      //     this.loading = false;
      //     console.error('Failed to send OTP');
      //   },
      // });
    }
    }

  };
</script>

<style scoped>
.card {
  box-shadow: rgba(0, 0, 0, 0.25);
  padding: 30px 25px;
  border-radius: 10px;
  max-width: 1018px;
  margin: auto;
}

.profile-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
}
.custom-date-picker{
  width: 100% !important;
}
.profile-container {
  position: relative; /* Make the container relative to position the cam icon inside it */
  display: inline-block;
}

.profile-img {
  display: block;
  width: 100px;
  height: 100px;
  object-fit: cover; /* Ensures the image covers the box without distorting */
  border-radius: 10px;
}

.cam-icon {
  position: absolute;
  bottom: 8px; /* Position it near the bottom of the image */
  right: 8px; /* Position it near the right side of the image */
  z-index: 10; /* Ensure the icon appears on top of the image */
  cursor: pointer;
  background-color: white; /* Optionally add a background to make the icon more visible */
  border-radius: 50%; /* Optionally make the icon circular */
  padding: 5px; /* Add some padding around the icon */
}
</style>
