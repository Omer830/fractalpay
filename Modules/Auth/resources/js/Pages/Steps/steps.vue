<template>
    <div>
        <logo-header @back-clicked="handleBack" :hasDivider="true" />
        <div class="step-box mt-3 px-2">
            <div class="text-start mb-3 mt-5">
                <app-typography variant="h4" fontWeight="600">Take the First Step</app-typography>
            </div>
            <hr class="mb-4 mt-0 hr" />
            <capsule-field :isCompleted="isProfileComplete" label="Create Profile" @onclick="handleProfile" image-source="step-profile" :width="33"
                :height="32" />
          <capsule-field :isCompleted="isProofOfIdentityComplete" label="Proof of Identity" @onclick="handleProof" image-source="step-identity" :width="33"
                         :height="32" />
            <capsule-field label="Add Payment Method" :isCompleted="isPaymentComplete" @onclick="handlePayment" image-source="step-payment" :width="33"
                :height="32" />

            <capsule-field label="Start Sharing or Setup Split Payments" @onclick="handleSharing"
                image-source="step-share" :width="33" :height="32" />
            <br>
            <reuseable-button width="100" @click="handleContinue">Continue</reuseable-button>
            <div class="my-3">
                <back-button endIcon btnText="Skip to Dashboard" textColor="#4682be" image="right-icon" height="15px"
                    width="9px" @click="handleBackClick" fontSize="22px" />
            </div>
        </div>
    </div>
</template>

<script>
import { LogoHeader, CapsuleField, ReuseableButton, AppTypography, BackButton } from "@/components/index.js";

export default {
    name: 'OtpPage',
    components: {
        LogoHeader,
        CapsuleField,
        ReuseableButton,
        AppTypography,
        BackButton
    },
    props: {
    profile_status: String,
    proofOfIdentityStatus: String,
    paymentStatus: String,

  },
  computed: {

    isProfileComplete() {

      return this.profile_status && this.profile_status.completion_percentage == 100;
    },
    isProofOfIdentityComplete() {
      return this.proofOfIdentityStatus && this.proofOfIdentityStatus.completion_percentage >= 100;
    },
    isPaymentComplete() {
      return this.paymentStatus && this.paymentStatus.isPayment;

    }
  },
  mounted() {

  },
    data() {
        return {
        };
    },
    methods: {
        // any methods if needed
        handleBack() {
          this.$inertia.visit('/welcome');
        },
      handleContinue() {
        if (!this.isProfileComplete) {
          this.$inertia.visit('/profile'); // Navigate to Profile Creation page
        } else if (!this.isProofOfIdentityComplete) {
          this.$inertia.visit('/doc-type'); // Navigate to Proof of Identity page
        } else if (!this.isPaymentComplete) {
          this.$inertia.visit('/payment-method'); // Navigate to Payment Method page
        } else {
          this.$inertia.visit('/insurance'); // Navigate to the next step if all previous steps are complete
        }
      },
        handleProfile() {
          this.$inertia.visit('/profile');
        },
        handleProof() {
          if (!this.isProfileComplete) {
            this.$toast('Please complete Profile first.', 'error'); // Show alert if Profile is incomplete
            return;
          }
          this.$inertia.visit('/doc-type'); // Navigate to Proof of Identity page
        },
        handlePayment() {
          if (!this.isProfileComplete) {
            this.$toast('Please complete Profile first.', 'error'); // Show alert if Profile is incomplete
            return;
          }
          if (!this.isProofOfIdentityComplete) {
            this.$toast('Please complete Proof of Identity first.', 'error'); // Show alert if Proof of Identity is incomplete
            return;
          }
          this.$inertia.visit('/payment-method'); // Navigate to Payment Method page
        },
        handleSharing() {
          if (!this.isProfileComplete) {
            this.$toast('Please complete Profile first.', 'error'); // Show alert if Profile is incomplete
            return;
          }
          if (!this.isProofOfIdentityComplete) {
            this.$toast('Please complete Proof of Identity first.', 'error'); // Show alert if Proof of Identity is incomplete
            return;
          }
          if (!this.isPaymentComplete) {
            this.$toast('Please complete Payment Method first.', 'error'); // Show alert if Payment Method is incomplete
            return;
          }
          this.$inertia.visit('/invitation-method'); // Navigate to Sharing page
        },
      handleBackClick() {
        if (this.isProfileComplete && this.isProofOfIdentityComplete && this.isPaymentComplete) {
          this.$inertia.visit('/dashboard');
        } else {
          this.$toast('Complete profile, identity verification, and payment method first.', 'error');
        }
      }

    }
}
</script>

<style scoped>
.steps {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: column;
    width: fit-content;
    padding: 30px;
    justify-content: center;
    text-align: center;
}

.step-box {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: column;
    width: fit-content;
    justify-content: center;
    text-align: center;
    min-width: 485px
}

.hr {
    color: #4682BE;
    margin: 25px -10px;
}
</style>