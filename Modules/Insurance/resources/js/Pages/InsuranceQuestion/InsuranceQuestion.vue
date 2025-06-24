<template>
    <div class="insurance-wrapper">
      <div v-if="loading" class="loader-main">
        <div class="loader"></div>
      </div>
        <logo-header @back-clicked="handleBack" />
        <div v-if="!isCancelClicked" class="">
            <div v-if="!isPremiumDetails" class="modal-wrapper d-flex flex-column align-items-center gap-4">
                <app-typography variant="h4" fontWeight="700">
                    {{ title }}
                </app-typography>
                <div class="d-flex gap-3">
                    <reuseable-button @click="handleIsOk" v-if="isOk" outline textColor="#4682BE" class="px-5">
                        Yes
                    </reuseable-button>
                    <reuseable-button @click="handleIsCancel" v-if="isCancel" outline textColor="#4682BE" class="px-5">
                        No
                    </reuseable-button>
                </div>
<!--                <reuseable-button class="w-100" @click="handleContinue" v-if="isContinue">-->
<!--                    Continue-->
<!--                </reuseable-button>-->
            </div>
            <div v-else>
                <premium-details
                    :errors="errors"
                    :insuranceNames="insuranceNames"
                    :termsPeriods="termsPeriods"
                    :insuranceData="insuranceData"
                    @submit="handleFormSubmit" ref="childRef" />
                <common-footer :isBackBtn="false" :isContinueBtn="false" :isSendInvite="false" :isSaveBtn="true"
                    @saveBtn="handleSave" />
            </div>
        </div>
        <div v-else>
            <success-alert :title='alertTitle' :isBackButton="cameFromDashboard" :desc='page.props.successMessage' @back="backtoDashboard" @invite="handleInvite" />
        </div>
    </div>
</template>

<script>
import { LogoHeader, AppTypography, ReuseableButton, } from "@/components/index.js";
import PremiumDetails from "../../../../../../resources/js/components/InsurancePremiumDetails/premiumDetails.vue";
import SuccessAlert from "../../../../../../resources/js/components/SuccessAlert/successAlert.vue";
import CommonFooter from "../../../../../../resources/js/components/CommonFooter/commonFooter.vue";

export default {
    components: {
        LogoHeader,
        AppTypography,
        ReuseableButton,
        PremiumDetails,
        SuccessAlert,
        CommonFooter
    },
    props: {
        title: {
            type: String,
            default: 'Do you have private health insurance'
        },
        isOk: {
            type: Boolean,
            default: true
        },
        isCancel: {
            type: Boolean,
            default: true
        },
        isContinue: {
            type: Boolean,
            default: true
        },
      errors: {
        type: Object,
        default: () => ({})
      },
      insuranceNames: {
        type: Array,
        default: () => ([]),
      },
      termsPeriods: {
        type: Array,
        default: () => ([]),
      },
      successMessage:String
    },
    data() {
        return {
          insuranceData:{},
            alertTitle: 'All Done',
            alertDesc: this.successMessage,
            loading: false,
            isPremiumDetails: false,
            isCancelClicked: false,
          formValues: this.$inertia.form({
            company_name: '',
            card_number: '',
            policy_number: '',
            terms: '',
            amount: '',
            cameFromDashboard: false

          }),
        }
    },
  mounted() {
    this.fetchInsurance()
    if (this.$store.getters.getPageComeFromName === 'dashboard') {
      this.cameFromDashboard = true;
    }
  },
  computed: {
    page() {
      console.log(this.$page)
      return this.$page;
    }
  },
  methods: {
        handleIsOk() {
            this.isPremiumDetails = true
        },
        handleIsCancel() {
            this.isCancelClicked = true
        },
        handleContinue() {

            this.$emit('isContinue')
        },
        handleBack() {
          window.history.back()
            // if (!this.isPremiumDetai
          // ls && !this.isCancelClicked) {
            //     this.$inertia.visit('/steps')
            // } else {
            //     this.isPremiumDetails = false;
            //     this.isCancelClicked = false;
            // }
        },
      handleFormSubmit(data) {
        // this.isCancelClicked = true
        this.loading = true
        console.log('Form data received:',JSON.stringify(data));

          Object.keys(data).forEach(key => {
            this.formValues[key] = data[key];
          });

          this.formValues.post('/store-insurance-detail', {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => this.formValues.reset('loading'),
            onError: (errors) => {
              console.error('Submission errors:', errors);
              this.loading = false
              this.$toast('Failed to add insurance details', 'error');
            },
            onSuccess: (response) => {
              this.isCancelClicked = true
              this.$toast('Insurance added successfully' , 'success')
              this.loading = false
              if (response.props.success) {

              }
            }
          });
      },
        handleSave() {
          console.log('asd')
          this.$refs.childRef.handleSubmit();
            // this.isCancelClicked = true
        },
    fetchInsurance() {
      this.loading = true
      this.$inertia.get('/insurance', {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
          this.loading = false
          this.insuranceData = page.props?.insuranceDetails?.data;
          if(this.insuranceData){
            this.isPremiumDetails = true
          }
          if (page.props && page.props.insuranceDetails) {
            this.setFormData(page.props.insuranceDetails.data);
          }

        }
      });
    },
        handleInvite() {
            this.$inertia.visit('/invitation-method')
        },
    backtoDashboard() {
            this.$inertia.visit('/dashboard')
        }
    }
}
</script>

<style scoped>
.modal-wrapper {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    padding: 38px 43px;
    border-radius: 10px;
    max-width: 565px;
    margin: auto;
}
</style>