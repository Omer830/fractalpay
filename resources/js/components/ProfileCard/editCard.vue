<template>
  <div class="container">
    <div class="card">
      <div class="header d-flex justify-content-between flex-wrap align-items-center gap-3">
        <div class="d-flex gap-4 align-items-center">
          <div class="profile-container">
            <img class="profile-img" :src="avatar" alt="Profile Picture" @click="handleAvatarChange" />
                       <image-svg class="cam-icon" width="32px" height="32px" name="cam-icon" />
          </div>

          <div>
            <app-typography variant="h5" fontWeight="500">
              {{ userData.name }}
            </app-typography>
            <app-typography variant="body3" color="#A1A0A0">I'm
              {{ userStatus }}
            </app-typography>
          </div>
        </div>
        <!--        <reuseable-button @click="updateVarification" outline textColor="#4682BE" btnHeight="51px">Update-->
        <!--          Varification-->
        <!--        </reuseable-button>-->
      </div>

      <div class="form-wrapper">
        <app-typography variant="h4" fontWeight="500" class="my-4">
          {{ mainTitle }}
        </app-typography>
        <form @submit.prevent="handleSubmit">
          <div class="row gy-3">
           <div class="col-md-6">
  <form-group-input
      type="text"
      label="Name"
      placeholder="Enter name"
      :value="formData.name"
      v-model="formData.name"
      borderRadiusClass="border-sm"
      image-source="user"
      :height="24"
      :width="25"
      :error="errors.name || nameError"
      @blur="validateInput('name')"
      :required="true"
  />
</div>
            <div class="col-md-6">
              <form-group-input
                  type="text"
                  label="Address"
                  placeholder="Enter address"
                  :value="formData.address"
                  v-model="formData.address"
                  borderRadiusClass="border-sm"
                  image-source="location"
                  :height="25"
                  :width="25"
                  :error="errors.address || addressError"
                  @blur="validateInput('address')"
                  :required="true"
                  :readOnly="false"
              />
            </div>
            <div class="col-md-6">
              <form-group-input
                  type="text"
                  label="Postal code"
                  placeholder="Enter postal code"
                  v-model="formData.postal_code"
                  :value="formData.postal_code"
                  borderRadiusClass="border-sm"
                  image-source="postal"
                  :height="25"
                  :width="25"
                  :error="errors.postal_code || postalCodeError"
                  @blur="validateInput('postalCode')"
                  :required="true"
                  :readOnly="true"
              />
            </div>
            <div class="col-md-6">
              <app-select
                  type="text"
                  label="Country"
                  :height="25"
                  :width="25"
                  borderRadiusClass="border-sm"
                  image-source="country"
                  :options="countries"
                  v-model="formData.country_id"
                  optionLabel="name"
                  optionValue="id"
                  placeholder="Select an option" @blur="validateInput('country_id')"
                  :error="errors.country_id || countryError"
                  :required="true"
                  :readOnly="true"
              />

            </div>
            <div class="col-md-6">
              <app-select
                  type="text"
                  label="State"
                  :height="25"
                  :width="25"
                  borderRadiusClass="border-sm"
                  image-source="state" :options="states" v-model="formData.state"
                  optionLabel="name"
                  optionValue="slug"
                  placeholder="Select an option" @blur="validateInput('state')"
                  :error="errors.city || stateError"
                  :required="true"
                  :readOnly="true"
              />
            </div>
            <div class="col-md-6">
              <app-select
                  type="text"
                  label="City"
                  optionLabel="name"
                  optionValue="slug"
                  :height="25"
                  :width="25"
                  borderRadiusClass="border-sm"
                  image-source="city"
                  :options="cities"
                  v-model="formData.city"
                  placeholder="Select an option" @blur="validateInput('city')"
                  :required="true"
                  :error="errors.city || cityError"
                  :readOnly="true"
              />
            </div>
            <div class="col-md-6">
              <app-select
                  type="text"
                  label="Gender"
                  :height="24"
                  :width="25"
                  borderRadiusClass="border-sm"
                  image-source="user"
                  :options="selectGenderOption"
                  v-model="formData.gender"
                  placeholder="Select an option"
                  @blur="validateInput('gender')"
                  :error="errors.gender || genderError"
                  :readOnly="true"
                  :required="true"/>
            </div>
            <div class="col-md-6">
              <div>
                <app-typography  variant="body1" class="control-label mb-2"> Date Of Birth
                  <span class="required-asterisk">*</span>
                </app-typography>

                <el-date-picker
                    v-model="formData.date_of_birth"
                    type="date"
                    placeholder="Select date of birth"
                    format="DD-MM-YYYY"
                    value-format="DD-MM-YYYY"
                    :picker-options="datePickerOptions"
                    class="w-full custom-date-picker"
                    :error="errors.date_of_birth || dobError"
                    @blur="validateInput('date_of_birth')"
                    :required="true"
                    :clearable="false"
                    :disabled="true"
                />
              </div>
              <div class="pt-4" >
                <span v-if="errors.date_of_birth" class="error-message mt-3">{{ errors.date_of_birth }}</span>
                <span v-if="dobError" class="error-message">{{ dobError }}</span>
              </div>
            </div>

            <div class="col-md-6">
              <form-group-input
                  type="text"
                  label="User name"
                  :height="24"
                  :width="25"
                  placeholder="Enter user name"
                  v-model="formData.user_name"
                  :value="formData.user_name"
                  borderRadiusClass="border-sm" image-source="user"
                  :error="errors.user_name || userNameError"
                  @blur="validateInput('userName')"
                  :required="true"
                  :readOnly="true"
              />
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
  <common-footer @back="goBackHandler" :isBackBtn="false" buttonTitle="Submit" @continue="handleSubmit"/>
</template>

<script>
import {AppTypography, FormGroupInput, ReuseableButton} from "@/components/index";
import PROFILE_AVATAR from "../../assets/images/owner-profile.png";
import MALE_AVATAR from "../../assets/images/Male.png";
import FEMALE_AVATAR from "../../assets/images/Female.png";
import CommonFooter from "../CommonFooter/commonFooter.vue";
import AppSelect from "../Select/appSelect.vue";
import {usePage} from "@inertiajs/vue3";

const userData = usePage();
export default {
  components: {
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
      mainTitle: "Personal Details",
      profile: this.$inertia.form({
        file: null,
        file_type: 'profile-image',
      }),
      formData: {
        name: "",
        address: "",
        postal_code: "",
        city: null,
        state: "",
        gender: "",
        date_of_birth: "",
        user_name: "",
        country_id: null
      },
      selectGenderOption: [
        {label: 'Male', value: 'male'},
        {label: 'Female', value: 'female'}
      ],
      selectCityOption: [],
      selectCountryOption: [],
      selectStateOption: [],
      datePickerOptions: {
        disabledDate(time) {
          // Example: Prevent future dates from being selected
          return time.getTime() > Date.now();
        },
        shortcuts: [
          {
            text: 'Today',
            onClick(picker) {
              picker.$emit('pick', new Date());
            },
          },
          {
            text: 'Yesterday',
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', date);
            },
          },
          {
            text: 'One Week Ago',
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', date);
            },
          },
        ],
      },
      nameError: "",
      addressError: "",
      postalCodeError: "",
      cityError: "",
      stateError: "",
      countryError: "",
      genderError: "",
      dobError: "",
      userNameError: "",
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
      if (this.userProfile) {
        this.formData.name = this.userProfile.data.first_name + ' ' + this.userProfile.data.last_name || '';
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
      this.$inertia.get('/edit-profile', { country: countrySlug }, {
        preserveState: true,
        preserveScroll: true, // Prevent scrolling up
        only: ['states']
      });
      setTimeout(() => {
      this.handleStateChange(this.formData.state);
    }, 2000); 
    },

    handleStateChange(stateSlug) {
    
    // Fetch cities for the selected state
    this.$inertia.get('/edit-profile', { state: stateSlug }, {
      preserveState: true,
      preserveScroll: true, // Prevent scrolling up
      only: ['cities'],
      onSuccess: (response) => {
        if (response.props.cities && response.props.cities.length > 0) {
          this.cities = response.props.cities; // Populate city options
        } else {
          this.cities = []; // Clear cities if no data is returned
        }
      },
      onError: () => {
        this.cities = []; // Clear cities on error
        this.$toast('Failed to fetch cities. Please try again.', 'error');
      },
    });
  },
  
  handleAvatarChange() {
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = '.webp,.png,.jpg,.jpeg,.gif'; // Restrict file types in the file input
    fileInput.onchange = (e) => {
      const file = e.target.files[0];
      if (file) {
        const validTypes = ['image/webp', 'image/png', 'image/jpg', 'image/jpeg', 'image/gif'];
        const maxFileSize = 2 * 1024 * 1024; // 2MB limit

        // Validate file type
        if (!validTypes.includes(file.type)) {
          this.$toast('Invalid file type. Please select a webp, png, jpg, jpeg, or gif image.', 'error');
          return;
        }

        // Validate file size
        if (file.size > maxFileSize) {
          this.$toast('File size exceeds the 2MB limit. Please select a smaller image.', 'error');
          return;
        }

        this.selectedFile = file;
        this.profile.file = file;

        const reader = new FileReader();
        reader.onload = (e) => {
          this.avatar = e.target.result;
          this.handleSubmitProfileImage();
        };
        reader.readAsDataURL(file);
      }
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
        this.$toast('Profile image upload successful', 'success');
      },
      onError: () => {
        this.$toast('Upload failed. Please try again.', 'error');
      },
      forceFormData: true,
    });
  },



  },
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
