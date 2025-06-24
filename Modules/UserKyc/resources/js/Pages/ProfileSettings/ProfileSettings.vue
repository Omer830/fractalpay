<template>
  <div class="container-fluid">
    <div class="row gy-4">
      <!--            <div class="col-sm-12">-->
      <!--                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"-->
      <!--                    :isActionButtons="false" :isUserName="false" :isDivider="true" />-->
      <!--            </div>-->
      <div class="col-md-12">
        <div v-if="loading" class="loader-main">
          <div class="loader"></div>
        </div>
        <logo-header @back-clicked="handleBackClick" :isDivider="true" />
        <div v-if="profileStatus">
        <profile-card
            :errors="errors"
            :userProfile="userProfile"
            :countries="countries"
            :states="states"
            :cities="cities"
            @form-submitted="onFormSubmitted"
            @back-btn="handleBack"
            :isVerificationBtn="true" />
        </div>
        <div v-else>
          <success-alert title='Awesome!' titleTextColor="#70C043" desc='Your profile all set up
            and ready to roll!' btnText="Next Step: Proof Of Identity" :isBackButton="false" @invite="handleInvite" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ProfileCard, TitleActionBar,} from "@/components/index.js";
import { LogoHeader } from "@components";
import SuccessAlert from "@/components/SuccessAlert/successAlert.vue";
export default {
  components: {
    SuccessAlert,
    ProfileCard,
    TitleActionBar,
    LogoHeader
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

      this.formValues.post('/update-profile', {
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
            this.$inertia.visit('/steps')

            // Navigate or perform further actions
            // this.$inertia.visit('signup/otp');
          }
        }
      });
    },

    handleBack() {
      this.$inertia.visit('steps');
      // window.history.back();
    },
    handleBackClick() {
      this.$inertia.visit('steps');
      // window.history.back();
    }
  },
}
</script>

<style scoped></style>
