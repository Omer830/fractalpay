<template>
  <auth-layout>
  <div class="container-fluid mt-5">
    <div class="row gy-4">
      <!--            <div class="col-sm-12">-->
      <!--                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"-->
      <!--                    :isActionButtons="false" :isUserName="false" :isDivider="true" />-->
      <!--            </div>-->
      <div class="col-md-12">
        <div v-if="loading" class="loader-main">
          <div class="loader"></div>
        </div>
<!--        <logo-header @back-clicked="handleBackClick" :isDivider="true" />-->
        <div v-if="profileStatus">
          <div class="container">
            <div class="">
              <div class="custom-tabs mx-2">
      <span
          v-for="(tab, index) in tabs"
          :key="index"
          class="tab-item"
          :class="{ active: activeTab === tab }"
          @click="activeTab = tab"
      >
        {{ tab }}
      </span>
              </div>

              <div v-if="activeTab === 'Personal Details'">
          <edit-profile-card
              :errors="errors"
              :userProfile="userProfile"
              :countries="countries"
              :states="states"
              :cities="cities"
              @form-submitted="onFormSubmitted"
              @back-btn="handleBack"
              :isVerificationBtn="true" />
              </div>

              <div v-if="activeTab === 'Account Details'">
                <AccountDetails :userProfile="userProfile"
                />
              </div>
              <div v-if="activeTab === 'Security'">
                <SecurityQuestions :userProfile="userProfile"/>
              </div>
              <div v-if="activeTab === 'Uploaded Documents'">
              <KYCDocuments :userProfile="userProfile"/>
              </div>
            </div>
          </div>
        </div>

        <div v-else>
          <success-alert title='Awesome!' titleTextColor="#70C043" desc='Your profile all set up
            and ready to roll!' btnText="Next Step: Proof Of Identity" :isBackButton="false" @invite="handleInvite" />
        </div>
      </div>
    </div>
  </div>
  </auth-layout>
</template>

<script>
import { EditProfileCard, TitleActionBar, SecurityQuestions , AccountDetails , KYCDocuments} from "@/components/index.js";
import { LogoHeader } from "@components";
import SuccessAlert from "@/components/SuccessAlert/successAlert.vue";
export default {
  components: {
    SuccessAlert,
    EditProfileCard,
    TitleActionBar,
    LogoHeader,
    SecurityQuestions,
    AccountDetails,
    KYCDocuments
  },
  props: {
    errors: {
      type: Object,
      default: () => ({})
    },
    countries: Array,
    cities: Array,
    states: Array,
    userProfile: Object,
  },
  data() {
    return {
      header: {
        title: "Profile"
      },
      tabs: ["Personal Details", "Account Details", "Security", 'Uploaded Documents'], // Tabs
      activeTab: "Personal Details", // Default active tab
      formValues: this.$inertia.form({
        name: "",
        address: "",
        postal_code: "",
        city: null,
        state: "",
        gender: "",
        date_of_birth: "",
        user_name: "",
        country_id:null

      }),
      loading:false,
      title: "Select Invitation Method",
      subtitle: "QR Code, Email, SMS or WhatsApp",
      profileStatus: true,
    };
  },
  methods: {

    onFormSubmitted(formData) {
      // Directly assign new values to formValues properties
      this.loading = true;
      
      Object.keys(formData).forEach(key => {
        this.formValues[key] = formData[key];
      });

          this.formValues.post('/update-edit-profile', {
            preserveScroll: true,
            preserveState: true,
        onFinish: () => this.formValues.reset('loading'),
        onError: (errors) => {
          this.loading = false;
          // Handle errors, maybe log them or set an error message state
        },
        onSuccess: (response) => {

          if (response.props.success) {
            // Display a success message
            this.$toast('Profile updated successfully!',  'success' );
            this.loading = false;
            this.$inertia.visit('/edit-profile')

            // Navigate or perform further actions
            // this.$inertia.visit('signup/otp');
          }
        }
      });
    },

    handleBack() {
      // this.$inertia.visit('steps');
      // window.history.back();
    },
    handleBackClick() {
      // this.$inertia.visit('steps');
      // window.history.back();
    }
  },
}
</script>

<style scoped>
.custom-tabs {
  display: flex;
  border-bottom: 1px solid #ddd;
  margin-bottom: 16px;
}


/* Individual Tab Item */
.tab-item {
  padding: 10px 20px;
  cursor: pointer;
  font-weight: 400;
  color: #7a7a7a;
  position: relative;
  text-align: center;
  flex: 1;
}

/* Active Tab Style */
.tab-item.active {
  color: #4682BE; /* Blue color for active tab */
  font-weight: 600;
}

/* Underline Effect for Active Tab */
.tab-item.active::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 2px;
  background-color: #4682BE; /* Blue underline */
  transition: width 0.3s ease;
}

/* Hover Effect (Optional) */
.tab-item:hover {
  color: #4682BE;
}
</style>
