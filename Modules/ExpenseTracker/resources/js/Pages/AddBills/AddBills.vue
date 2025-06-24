<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
  <div v-if="!paymentStatus" class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="header text-center mb-4">
                        <app-typography variant="h4" fontWeight="500">{{ cardDetails.title
                            }}</app-typography>
                        <app-typography variant="body2" color="#6E6E6E" fontWeight="300">
                            {{ cardDetails.subTitle }}
                        </app-typography>
                    </div>
<!--                    <div class="capsule-wrapper" v-for="(item, index) in capsulesArray" :key="index">-->
<!--                        <capsule-field capPadding="15px" capHeight="78px" capRadius="12px" :label="item.title"-->
<!--                            :image-source="item.icon" :width="41" :height="41" />-->
<!--                    </div>-->

                  <ReusablePaymentMethod :paymentMethods="paymentMethods"/>

                    <hr>
                  <div  @click="useWallet()">
                  <capsule-field
                      capPadding="15px"
                      capHeight="58px"
                      capRadius="12px"
                      :label="`Use Wallet Amount ($${$store.getters.getBillPayableAmount})`"
                      image-source="wallet-round-bg-icon"
                      :width="41"
                      :height="41"
                      :isCompleted="formData.is_wallet"
                  />
                  </div>

                  <div class="detail-wraper ">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                            <app-typography variant="body1" color="#4682BE" fontWeight="300">
                                Payable Bill Amount
                            </app-typography>
                            <app-typography variant="body3" color="#70C043" fontWeight="300">
                                ${{this.$store.getters.getBillPayableAmount}}
                            </app-typography>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <app-typography variant="body1" color="#4682BE" fontWeight="300">
                                Total Amount In your Wallet
                            </app-typography>
                            <app-typography variant="body3" color="#70C043" fontWeight="300">
                              ${{ walletAmount }}
                            </app-typography>
                        </div>

                    </div>

                  <div class="mt-2" v-if="formData.bill_id.length === 1">

                    <form-group-input
                        type="text"
                        label="Amount"
                        placeholder="Enter Amount"
                        :value="formData.amount"
                        v-model="formData.amount"
                        borderRadiusClass="border-sm"
                        :height="25"
                        :width="25"
                        :required="true"
                    />
                  </div>
                </div>
            </div>
            <div class="col-lg-12">
                <common-footer @back="handleBack" @continue="handleContinue" />
            </div>
        </div>
    </div>
    <div v-else>
      <success-alert :title='alertTitle'
                     :isBackButton="handleBack"
                     backBtnText="Back"
                     btnText="Expense Tracker"
                     :desc='alertDesc'
                     @invite="handleInvite" />
    </div>
  </auth-layout>
</template>

<script>
import { TitleActionBar, AppTypography, CapsuleField, CommonFooter,PaymentMethod ,ReusablePaymentMethod } from "@/components/index";
import SuccessAlert from "@/components/SuccessAlert/successAlert.vue";
import ImageSvg from "../../../../../../resources/js/components/ImageSvg/imageSvg.vue";
import {FormGroupInput} from "@/components/index.js";
import Swal from "sweetalert2";
export default {
    components: {
      FormGroupInput,
      ImageSvg,
      SuccessAlert,
        AppTypography,
        TitleActionBar,
        CapsuleField,
        CommonFooter,
      PaymentMethod,
      ReusablePaymentMethod
    },
    data() {
        return {
          alertTitle: 'Successfully Paid',
          alertDesc: 'your pending bills paid successfully',
            header: {
                title: "Add Bill"
            },
            cardDetails: {
                title: 'Select your Payment Method ',
                subTitle: 'Select your Payment Method for transaction',
            },
            capsulesArray: [
                { icon: 'bank-icon', title: 'Bank Account (12345678 - 123-222)' },
                { icon: 'credit-card-icon', title: 'Credit/Debit Card **** 5555' },
            ],
          formData: this.$inertia.form({
            type: "payment",
            currency: "AUD",
            amount: null,
            bill_id: [],
            payment_method_id: null,
            is_wallet: false,


          }),
          paymentMethods: [],
          walletAmount: null,
          paymentStatus: false,
          loading: false,
        }
    },
  mounted() {
    this.getWalletAmount()
    this.$store.commit('SET_PAYMENT_METHOD_DETAIL','')
    this.formData.amount = JSON.parse(this.$store.getters.getBillPayableAmount);
    this.formData.bill_id = this.$store.getters.getBillsIds;


  },
  methods: {
        handleCancel() {
            this.$router.push("./investment-dashboard")
        },
        handleBack() {
            this.$inertia.visit('/payment-bill-summary')
        },
    handleInvite(){
          this.$inertia.visit('/expense-tracker')
        },
        handleContinue() {
          // Show confirmation dialog
          Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to proceed with the payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4682BE',
            cancelButtonColor: '#bcbcbc',
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel',
          }).then((result) => {
            if (result.isConfirmed) {
              // Proceed with the original handleContinue logic
              this.formData.bill_id = this.$store.getters.getBillsIds;
              this.formData.payment_method_id = this.$store.getters.getPaymentMethodDetail.id;

              if (!this.formData.type) {
                this.$toast('The type field is required', 'error');
                return;
              }

              if (!this.formData.currency) {
                this.$toast('The currency field is required', 'error');
                return;
              }

              if (!this.formData.amount || this.formData.amount <= 0) {
                this.$toast('The amount field is required and must be greater than zero', 'error');
                return;
              }

              if (!this.formData.bill_id) {
                this.$toast('The bill ID field is required', 'error');
                return;
              }

              if (!this.formData.payment_method_id) {
                this.$toast('The payment method ID field is required', 'error');
                return;
              }

              if (this.formData.is_wallet && !this.formData.payment_method_id) {
                this.$toast('Please select a bank account with wallet', 'error');
                return;
              }

              this.loading = true;
              this.formData.post('/add-bill', {
                onFinish: () => this.formData.reset('loading'),
                onSuccess: (response) => {
                  this.$toast('Bill paid successfully', 'success');
                  this.loading = false;
                  this.paymentStatus = true;
                  this.$store.commit('SET_PAYMENT_METHOD_DETAIL', '');
                },
                onError: (error) => {
                  this.showErrors(error);
                  this.loading = false;
                },
              });
            }
          });
        },
    showErrors(errors) {
      if (errors && Object.keys(errors).length > 0) {
        Object.keys(errors).forEach(key => {
          this.$toast(`Error: ${errors[key]}`, 'error');
        });
      }
    },
      getWalletAmount() {
        this.loading = true;
        this.$inertia.get('/add-bill', {}, {
          preserveState: true,
          onSuccess: (page) => {
            this.loading = false
            this.walletAmount = page.props.wallet.data.available_balance;
            this.paymentMethods = page.props.paymentMethods.data;
            console.log(JSON.stringify(this.paymentMethods))



          }
        });

      },

    useWallet(){
          console.log('console.log' , this.formData.is_wallet)
         this.formData.is_wallet =! this.formData.is_wallet;
    },

    handlePaymentMethod(){

    }

    }
}
</script>

<style scoped>
.card {
    padding: 33px 140px;
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border-radius: 6px;
    max-width: 770px;
    margin: auto;

}

.detail-wraper {
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border: 1px solid#D0D3E8;
    border-radius: 12px;
    padding: 18px;
}
</style>