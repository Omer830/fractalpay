<template>

  <div>

    <div v-if="isMethodModal" class="">
      <div class="">


        <hr class="hr my-4"/>


        <!-- Show option when bankAccounts is empty -->
        <div v-if="bankAccounts.length === 0" class="pointer" @click="handleMethodClick('bank_account', {})">
          <capsule-field
              label="Add a Bank Account"
              image-source="bank-icon"
              :width="33" :height="32"/>
        </div>

        <div v-else>
          <div v-for="(account, index) in bankAccounts" :key="'bank-' + index" class="d-flex align-items-center">
            <div class="pointer flex-grow-1" @click="handleMethodClick('bank_account', account)">
              <capsule-field :is-completed="selectedMethodType === 'bank_account' && selectedMethod.id === account.id"
                             :label="'Bank Account (BSB ' + getBankDetail(account, 'routing_number') + ' A.No ' + getBankDetail(account, 'account_number') + ')'"
                             :image-source="'bank-icon'"
                             :width="33" :height="32"/>
            </div>

          </div>
        </div>

        <div v-if="creditCards.length === 0" class="pointer" @click="handleMethodClick('credit_card', {})">
          <capsule-field
              label="Add a Credit Card"
              image-source="credit-card-icon"
              :width="33" :height="32"/>
        </div>

        <!-- Loop through credit cards when populated -->
        <div v-for="(card, index) in creditCards" :key="'card-' + index" class="d-flex align-items-center">
          <div class="pointer flex-grow-1" @click="handleMethodClick('credit_card', card)">
            <capsule-field :is-completed="selectedMethodType === 'credit_card' && selectedMethod.id === card.id"
                           :label="'Credit card (****' + getCardDetail(card, 'card_number') + ')'"
                           :image-source="'credit-card-icon'"
                           :width="33" :height="32"/>
          </div>
          <!--          &lt;!&ndash; Cross Button &ndash;&gt;-->
          <!--          <button class="btn btn-link text-danger" @click="handleRemoveMethod(card)">-->
          <!--            &#x2716; &lt;!&ndash; X character &ndash;&gt;-->
          <!--          </button>-->
        </div>

        <div class="flex justify-end" v-if="bankAccounts.length > 0 || creditCards.length > 0">
          <el-dropdown >
            <button class="add-more-btn">
              Add More
              <ArrowDown style="width: 12px; height: 12px;"  />
            </button>
            <template #dropdown>
              <el-dropdown-menu >
                <el-dropdown-item @click="handleMethodClick('bank_account', {})">Bank Account</el-dropdown-item>
                <el-dropdown-item @click="handleMethodClick('credit_card', {})">Credit Card</el-dropdown-item>
              </el-dropdown-menu>
            </template>
          </el-dropdown>
        </div>



      </div>

    </div>
    <div v-else class="bank-details-card  p-3">
      <div class="card-header ps-0">
        <app-typography variant="h6" fontWeight="600">
          {{ cardMainTitle }}
        </app-typography>
      </div>
      <app-typography variant="body1" fontWeight="500" class="my-4">{{ cardSubTitle }}</app-typography>
      <div v-if="showBankAccountForm" class="form-wrapper">
        <account-detail-form />
      </div>
      <div v-else class="form-wrapper">
        <credit-card-form :intent="intent" />
      </div>
      <!--        <div class="d-flex justify-content-end mt-4">-->
      <!--            <reuseable-button @click="handleContinue" class="px-5">Continue</reuseable-button>-->
      <!--        </div>-->
    </div>
  </div>
</template>

<script>
import {LogoHeader, AppTypography, ReuseableButton, CapsuleField} from "@/components/index";
import AccountDetailForm from "./accountDetailForm.vue";
import CreditCardForm from "./creditCardForm.vue";
import Swal from "sweetalert2";
import {ArrowDown} from "@element-plus/icons-vue";

export default {
  components: {
    ArrowDown,
    AccountDetailForm,
    ReuseableButton,
    AppTypography,
    CreditCardForm,
    LogoHeader,
    CapsuleField
  },
  props: {

    cardSubTitle: {
      type: String,
      default: ' '
    },
    intent: Object,

    paymentMethods: Array,
  },
  data() {
    return {
      bankAccounts: [],
      creditCards: [],
      isMethodModal: true,
      showBankAccountForm: true,
      loading: false,
      paymentMethod: null,
      cardMainTitle: 'Bank Account Details',
      methodsArray: [
        {label: 'Bank Account', icon: 'bank-icon'},
        {label: 'Credit card', icon: 'credit-card-icon'},
      ],
      selectedMethodType: '',
      selectedMethod: {},
    }
  },

  watch: {
    paymentMethods(newVal) {
      if (newVal && newVal.length) {
        this.getPaymentMethod();
      }
    }
  },
  computed: {},
  methods: {
    handleBack() {
      if (this.isMethodModal === false) {
        this.isMethodModal = true
      } else {
        window.history.back()
        // this.$inertia.visit('/steps')
      }
    },
    handleMethodClick(type, method) {
      this.selectedMethodType = type;
      this.selectedMethod = method;
      console.log( this.selectedMethodType  , this.selectedMethod)
      if (type === 'bank_account') {
        this.showBankAccountForm = true;
        this.cardMainTitle = 'Bank Account Details';


      } else if (type === 'credit_card') {
        this.cardMainTitle = 'Credit Card Details';
        this.showBankAccountForm = false;

      }
      if (!method || !method.id) {
        this.isMethodModal = false;
      }
      const methodDetails = {
        id: method.id,
        type: method.type,
        bankNumber: method.attribute.find(attr => attr.key === "account_number")?.value || '',
        bankName: method.attribute.find(attr => attr.key === "bank_name")?.value || '',
        userName: method.attribute.find(attr => attr.key === "account_holder_name")?.value || '',
        routingNumber: method.attribute.find(attr => attr.key === "routing_number")?.value || '',
      };

      this.$store.commit('SET_PAYMENT_METHOD_DETAIL', methodDetails);
      // this.$emit('method-clicked', { type, method });
    },

    handleContinue() {
      this.$emit('continue')
    },

    getPaymentMethod() {
      console.log(JSON.stringify(this.paymentMethods) , 'sb')
      this.paymentMethod = this.paymentMethods;
        console.log(this.paymentMethod);
      this.bankAccounts = this.paymentMethod.filter(method => method.type === 'bank_account');

      this.creditCards = this.paymentMethod.filter(method => method.type === 'credit_card');
    },
    getBankDetail(account, key) {
      const detail = account.attribute.find(attr => attr.key === key);
      return detail ? detail.value : '';
    },

    getCardDetail(card, key, masked = true) {
      const detail = card.attribute.find(attr => attr.key === key);
      if (!detail) return '';

      if (!masked) {
        return detail.value;
      }
      return detail.value.slice(-4);
    },

    handleRemoveMethod(method) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this payment method!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4682BE',
        cancelButtonColor: '#bcbcbc',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          // Simulating method removal and logging
          console.log('Method removed:', method);

          // Perform your removal logic here
          this.loading = true;
          // Example: Remove method from bankAccounts or creditCards array
          if (method.type === 'bank_account') {
            this.bankAccounts = this.bankAccounts.filter(acc => acc.id !== method.id);
          } else if (method.type === 'credit_card') {
            this.creditCards = this.creditCards.filter(card => card.id !== method.id);
          }

          this.loading = false;

          Swal.fire({
            title: 'Removed!',
            text: 'The payment method has been removed.',
            icon: 'success',
            confirmButtonColor: '#4682BE'
          });
        }
      });

      console.log('Method to remove:', method);
    }

  }
}
</script>

<style scoped>
.bank-details-card {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  border-radius: 6px;
  max-width: 774px;
  margin: auto;
  border: none;
}

.payment-methode-modal {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  margin: 100px auto;
  max-width: 552px;
  border: none;
}

.hr {
  color: #4682BE
}
.el-tooltip__popper {
  margin: 0 !important; /* Remove any default margin */
  padding: 0 !important; /* Remove any default padding */
}
.add-more-btn{
  background: #4682BE;
  color: #fff;
  border-radius: 20px;
  border: 1px solid #4682BE;
  height: 30px;
  width: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 12px;
  gap: 4px;
}

.add-more-btn:focus-visible {
  border: none !important;
  outline: none !important;
  box-shadow: none !important;
}
</style>
