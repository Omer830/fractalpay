<template>
  <auth-layout>
    <div class="container-fluid mb-4">
      <div v-if="loadingStatus" class="loader-main">
        <div class="loader"></div>
      </div>

      <div v-if="paymentStatus" class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="true"
                            :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true"/>

        </div>

        <div class="col-lg-6">
          <summary-card class="h-[35rem]" mainTitle="Payment Details" firstHeading="Currency" :secondHeading="user.currency"
                        :detailsArray="billSummaryArray"/>
        </div>
        <div class="col-lg-6">
          <summary-card class="h-[35rem]" mainTitle="Payment Method" firstHeading="Method"
                        secondHeading="Bank" :detailsArray="bpayArray"/>
        </div>
        <div class="col-lg-12">
          <common-footer @back="handleBack" buttonTitle="Submit" @continue="handleContinue"/>
        </div>
      </div>

      <div v-else>
        <success-alert title='All Done'
                       :isBackButton="false"
                       desc='Funds Added Successfully'
                       btnText="Back To Dashboard"
                       @invite="backToDashboard" />
      </div>
    </div>
  </auth-layout>
</template>

<script>
import {
  TitleActionBar, SummaryCard, SummaryMainCard, CommonFooter, SuccessAlert
} from "@/components/index";

export default {
  components: {
    SuccessAlert,
    TitleActionBar,
    SummaryCard,
    SummaryMainCard,
    CommonFooter
  },
  data() {
    return {
      header: {
        title: "Summary",
        subTitle: ""
      },
      loadingStatus: false,
      paymentStatus: true,

      user: this.$inertia.form({
        type: this.$store.getters.getFundType === 'deposit' ? "deposit" : "transfer",
        amount: null,
        currency: '',
        frequency: "",
        start_date: "",
        payment_method_id: null,
        end_date: null,
        receiver_id: null
      }),
    };
  },

  computed: {
    billSummaryArray() {
      const fundDetails = this.$store.getters.getFundDetail;
      return [
        { title: "Frequency", detail: fundDetails.frequencyDisplay },
        { title: "Amount", detail: fundDetails.amount + '$' }
      ];
    },
    bpayArray() {
      const fundDetails = this.$store.getters.getPaymentMethodDetail;
      const details = [
        { title: "Account Title", detail: fundDetails.userName },
        { title: "Bank Name", detail: fundDetails.bankName, hide: fundDetails.type === "credit_card" },
        // { title: "Account No.", detail: fundDetails.bankNumber },
        {
          title: fundDetails.type === "credit_card" ? "Card No." : "Account No.",
          detail: fundDetails.type === "credit_card" ? fundDetails.card_number : fundDetails.bankNumber
        },
        {
          title: "Card Type",
          detail: fundDetails.type === "credit_card" ? "Credit Card" : "Bank Account"
        }
      ];

      // Filter out the bank name if type is credit_card
      return details.filter(item => !item.hide);
    }
  },

  created() {
    this.assignValues();
  },

  methods: {
    assignValues() {
      const fundDetails = this.$store.getters.getFundDetail;
      this.user.amount = fundDetails.amount;
      this.user.frequency = fundDetails.frequency;
      this.user.currency = fundDetails.currency;
      this.user.start_date = fundDetails.start_date;

      const paymentDetails = this.$store.getters.getPaymentMethodDetail;
      this.user.payment_method_id = paymentDetails.id;

      if (this.user.type === 'transfer') {
        this.user.receiver_id = this.$store.getters.getSelectedUserId;
      }
    },

    handleBack() {
      this.$inertia.visit('/payment-method')
      // window.history.back();

    },

    handleContinue() {
      if (this.user.type === 'deposit') {
        this.addDeposit();
      } else if (this.user.type === 'transfer') {
        this.transferFunds();
      }
    },

    addDeposit() {
      if (!this.user.start_date) {
        this.$toast('Start date is required', 'error');
        return;
      }
      this.loadingStatus = true;
      this.user.post('/add-deposit', {
        onSuccess: () => {
          this.$toast('Add fund successful', 'success');
          this.paymentStatus = false;
          this.loadingStatus = false;
        },
        onFinish: () => {
          // this.loadingStatus = false;
        },
        onError: (error) => {
          this.loadingStatus = false;
          this.showErrors(error);
        }
      });
    },

    transferFunds() {
      if (!this.user.start_date) {
        this.$toast('Start date is required', 'error');
        return;
      }

      this.user.receiver_id = this.$store.getters.getSelectedUserId;

      this.loadingStatus = true;
      this.user.post('/add-fund', {
        onSuccess: () => {
          this.$toast('Fund Transfer successful', 'success');
          this.paymentStatus = false;
          this.loadingStatus = false;
        },
        onFinish: () => {
          // this.loadingStatus = false;
        },
        onError: (error) => {
          this.loadingStatus = false;
          this.showErrors(error);
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

    backToDashboard() {
      this.$inertia.visit('/dashboard');
    }
  }
};
</script>

<style scoped></style>
