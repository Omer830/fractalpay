<template>
  <div class="container">
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="card">
      <div class="row">
        <!-- Left Column: Password Change Form -->
        <div class="col-md-6">
          <app-typography variant="h4" fontWeight="500" class="my-4">
            Change Password
          </app-typography>
          <form @submit.prevent="handlePasswordSubmit">
            <div class="">
              <form-group-input
                  type="password"
                  label="Old password"
                  placeholder="Enter address"
                  :value="formData.old_password"
                  v-model="formData.old_password"
                  borderRadiusClass="border-sm"
                  image-source="eye"
                  :width="20"
                  :height="16"
                  :error="errors.old_password || oldPasswordError"
                  @blur="validateInput('address')"
                  :required="true"
                  :readOnly="false"
              />
            </div>
            
            <div >
              <form-group-input
                  type="password"
                  label="New Password"
                  placeholder=""
                  :value="formData.new_password"
                  v-model="formData.new_password"
                  borderRadiusClass="border-sm"
                  image-source="eye"
                  :width="20"
                  :height="16"
                  :error="errors.new_password || newPasswordError"
                  @blur="validateInput('address')"
                  :required="true"
                  :readOnly="false"
              />

            </div>
           
            <div >
              <form-group-input
                  type="password"
                  label="Confirm Password"
                  placeholder=""
                  :value="formData.confirmPassword"
                  v-model="formData.confirmPassword"
                  borderRadiusClass="border-sm"
                  image-source="eye"
                  :width="20"
                  :height="16"
                  :error="errors.confirmPassword || confirmPasswordError"
                  @blur="validateInput('address')"
                  :required="true"
                  :readOnly="false"
              />
            </div>

            
            <div class="mt-3" @click="handlePasswordSubmit">
          <reuseable-button  class="logout-button" outline width="250px"
                             btnHeight="48px" textColor="#4682BE" fontSize="14px" round="sm">
            Change Password
          </reuseable-button>

        </div>
          </form>
        </div>

        <!-- Right Column: KYC Documents -->
<!--        <div class="col-md-6">-->
<!--          <app-typography variant="h4" fontWeight="500" class="my-4">-->
<!--            Uploaded Documents-->
<!--          </app-typography>-->
<!--          <div class="document-section ">-->
<!--            <div v-if="!userProfile.data.userDocument || userProfile.data.userDocument.length === 0">-->
<!--              <p>No documents uploaded yet.</p>-->
<!--            </div>-->
<!--            <div v-else class="document-grid">-->
<!--              <div-->
<!--                v-for="doc in userProfile.data.userDocument"-->
<!--                :key="doc.id"-->
<!--                class="document-card"-->
<!--              >-->
<!--                <div class="doc-thumbnail">-->
<!--                  <template v-if="doc.mime_type.startsWith('image/')">-->
<!--                    <img :src="doc.url" alt="Document Image" class="doc-image" />-->
<!--                  </template>-->
<!--                  <template v-else>-->
<!--                    <div class="pdf-placeholder">-->
<!--                      <i class="fas fa-file-pdf fa-3x text-danger"></i>-->
<!--                    </div>-->
<!--                  </template>-->
<!--                </div>-->
<!--                <div class="doc-info">-->
<!--                  <h6 class="doc-title">{{ doc.name }}</h6>-->
<!--                  <p class="doc-type">{{ doc.collection_name }}</p>-->
<!--                  <a :href="doc.url" target="_blank" class="doc-link">View</a>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
      </div>

      <!-- Security Question & Answers Section -->
      <div class="form-wrapper mt-5">
        <app-typography variant="h4" fontWeight="500" class="my-4">
          Security Question & Answers
        </app-typography>
        <form @submit.prevent="handleSecuritySubmit">
          <div class="row gy-3">
            <!-- Question 1 -->
            <div class="col-md-6">
              <app-select
                label="Please Select Question 1"
                :options="filteredQuestionList(1)"
                v-model="formData.securityQuestion1"
                optionLabel="question"
                optionValue="id"
                borderRadiusClass="border-sm"
                placeholder="Select an option"
                @blur="validateInput('securityQuestion1')"
                :error="securityQuestion1Error"
                :required="true"
              />
            </div>
            <div class="col-md-6">
              <form-group-input
                type="text"
                label="Answer 1"
                placeholder="Enter answer"
                v-model="formData.securityAnswer1"
                :value="formData.securityAnswer1"
                borderRadiusClass="border-sm"
                :error="securityAnswer1Error"
                @blur="validateInput('securityAnswer1')"
                :required="true"
              />
            </div>

            <!-- Question 2 -->
            <div class="col-md-6">
              <app-select
                label="Please Select Question 2"
                :options="filteredQuestionList(2)"
                v-model="formData.securityQuestion2"
                optionLabel="question"
                optionValue="id"
                borderRadiusClass="border-sm"
                placeholder="Select an option"
                @blur="validateInput('securityQuestion2')"
                :error="securityQuestion2Error"
                :required="true"
              />
            </div>
            <div class="col-md-6">
              <form-group-input
                type="text"
                label="Answer 2"
                placeholder="Enter answer"
                v-model="formData.securityAnswer2"
                :value="formData.securityAnswer2"
                borderRadiusClass="border-sm"
                :error="securityAnswer2Error"
                @blur="validateInput('securityAnswer2')"
                :required="true"
              />
            </div>

            <!-- Question 3 -->
            <div class="col-md-6">
              <app-select
                label="Please Select Question 3"
                :options="filteredQuestionList(3)"
                v-model="formData.securityQuestion3"
                optionLabel="question"
                optionValue="id"
                placeholder="Select an option"
                borderRadiusClass="border-sm"
                @blur="validateInput('securityQuestion3')"
                :error="securityQuestion3Error"
                :required="true"
              />
            </div>
            <div class="col-md-6">
              <form-group-input
                type="text"
                label="Answer 3"
                placeholder="Enter answer"
                v-model="formData.securityAnswer3"
                :value="formData.securityAnswer3"
                borderRadiusClass="border-sm"
                :error="securityAnswer3Error"
                @blur="validateInput('securityAnswer3')"
                :required="true"
              />
            </div>
          </div>
        </form>
        <div @click="handleSecuritySubmit">
          <reuseable-button
            class="logout-button"
            outline
            width="250px"
            btnHeight="48px"
            textColor="#4682BE"
            fontSize="14px"
            round="sm"
          >
            Submit
          </reuseable-button>
        </div>
      </div>
    </div>
  </div>
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
      mainTitle: "Security",
      loading:false,
      formData: {
        old_password: "",
        new_password: "",
        confirmPassword: "",
        securityQuestion1: null,
        securityAnswer1: "",
        securityQuestion2: null,
        securityAnswer2: "",
        securityQuestion3: null,
        securityAnswer3: ""
      },
      questionList: [],


      oldPasswordError: "",
      newPasswordError: "",
      confirmPasswordError: "",
      securityAnswer1Error: "",
      securityQuestion2Error: "",
      securityQuestion3Error: "",

      // Define validation rules for each input field


    };
  },
  mounted() {
    this.getSecurityquestion()
    this.getSecurityAnswer()
  },
  watch: {},

  methods: {


    async getSecurityquestion() {
      try {
        const res = await axios.get('/securityquestion');
        this.questionList = res?.data || [];
        console.log(JSON.stringify(this.questionList));
      } catch (err) {
        console.error('Failed to fetch notifications:', err);
      }
    },

    async getSecurityAnswer() {
      try {
        const res = await axios.get('/usersecurityanswer');
        // this.questionList = res?.data || [];
        // console.log(JSON.stringify(this.questionList));
      } catch (err) {
        console.error('Failed to fetch notifications:', err);
      }
    },

    filteredQuestionList(currentField) {
      const selectedIds = [
        this.formData.securityQuestion1,
        this.formData.securityQuestion2,
        this.formData.securityQuestion3
      ].filter((val, idx) => {
        // Filter out current field to allow re-selection
        return currentField !== idx + 1 && val !== null;
      });

      return this.questionList.filter(q => !selectedIds.includes(q.id));
    },
    async handlePasswordSubmit() {
      // Clear old errors
      this.oldPasswordError = "";
      this.newPasswordError = "";
      this.confirmPasswordError = "";

      // Basic validation
      if (!this.formData.old_password) {
        this.oldPasswordError = "Old password is required.";
      }
      if (!this.formData.new_password) {
        this.newPasswordError = "New password is required.";
      }
      if (!this.formData.confirmPassword) {
        this.confirmPasswordError = "Confirm password is required.";
      }

      if (
          this.oldPasswordError ||
          this.newPasswordError ||
          this.confirmPasswordError
      ) {
        return; // Stop submission if any field is empty
      }

      // Check if new and confirm passwords match
      if (this.formData.new_password !== this.formData.confirmPassword) {
        this.confirmPasswordError = "New password and confirm password do not match.";
        return;
      }

      this.loading = true;
      try {
        const passwordData = {
          old_password: this.formData.old_password,
          new_password: this.formData.new_password,
        };

        const response = await api.changePassword(passwordData);
        console.log(response)
        if (response) { // Check if the response status is 200 (OK)
          this.$toast("Password changed successfully", "success");
          console.log("Password changed successfully:", response.data);
          this.$inertia.visit('/login');

          // Optionally reset the form
          this.formData.old_password = "";
          this.formData.new_password = "";
          this.formData.confirmPassword = "";
        } else {
          // Handle unexpected response status or errors
          // this.$toast("Failed to change password", "error");
        }

        this.loading = false;
      } catch (error) {
        console.error("Error changing password:", error.response?.data || error.message);
        this.$toast("Error changing password", "error");
        this.loading = false;
      }
    },

    async handleSecuritySubmit() {
      const userId = this.userData.id;

      const answers = [
        {
          user_id: userId,
          security_question_id: this.formData.securityQuestion1,
          answer: this.formData.securityAnswer1,
        },
        {
          user_id: userId,
          security_question_id: this.formData.securityQuestion2,
          answer: this.formData.securityAnswer2,
        },
        {
          user_id: userId,
          security_question_id: this.formData.securityQuestion3,
          answer: this.formData.securityAnswer3,
        }
      ];

      console.log("Submitting answers:", JSON.stringify(answers));

      try {
        // Send all answers in a single request
        const response = await axios.post('/usersecurityanswer', answers);
        this.$toast('Security questions submitted successfully', 'success');
        console.log('Security questions submitted successfully:', response.data);
        // this.$emit('form-submitted');
      } catch (error) {
        console.error('Error submitting security answers:', error.response?.data || error.message);
        this.$toast('Error submitting security answers', 'error');
      }
    },


    validateInput(inputField) {

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

.document-section {
  max-height: 400px; /* Set the maximum height */
  overflow-y: auto; /* Enable vertical scrolling */
  padding-right: 10px; /* Add padding to avoid content being cut off by the scrollbar */
}

.document-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.document-card {
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 15px;
  background: #fafafa;
  text-align: center;
}

.doc-thumbnail {
  margin-bottom: 10px;
}

.doc-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 6px;
}

.pdf-placeholder {
  height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f0f0f0;
  border-radius: 6px;
}

.doc-title {
  font-size: 16px;
  font-weight: 600;
  margin: 5px 0;
}

.doc-type {
  font-size: 14px;
  color: #777;
}

.doc-link {
  font-size: 14px;
  color: #4682BE;
  text-decoration: underline;
}
</style>
