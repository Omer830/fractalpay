<template>
    <div class="card">
        <div class="card-header d-flex flex-column justify-content-center  align-items-center bg-white">
            <app-typography variant="h4" fontWeight="500">{{ title }}</app-typography>
            <app-typography variant="body1" color="#777E90">{{ subTitle }}</app-typography>
        </div>
      <div class="mt-4" v-if="headingDetail">
      <div class="payment-details d-flex px-[10rem]  justify-content-between pb-3 flex-wrap"
           v-for="(item, index) in updatedPaymentDetails" :key="index">
        <app-typography variant="body1" color="#646464">{{ item.desc }}</app-typography>
        <div class="amount">
          <app-typography variant="body3" :color="item.disable === true ? '#BEBEBE' : '#4682BE'">{{
              "$" + item.amount
            }}</app-typography>
        </div>
      </div>
      </div>
        <div class="card-body d-flex flex-column justify-content-center  align-items-center pb-5">



<!--            <div class="text-wrapper d-flex gap-1">-->
<!--                <app-typography variant="h4" color="#3A84C6" fontWeight="500">Drag to</app-typography>-->
<!--                <app-typography variant="h4" color="#777E90" fontWeight="500"> select Amount</app-typography>-->
<!--            </div>-->

<!--          <div class="toggle-container mt-3">-->
<!--            <div class="toggle-button" :class="{ active: isSender }" >-->
<!--              <div class="toggle-knob"></div>-->
<!--            </div>-->
<!--            <div class="role-labels" >-->
<!--              <span :class="{ active: !isSender }" @click="toggleRole('AUD')">AUD</span>-->

<!--              <span :class="{ active: isSender }" @click="toggleRole('USD')">USD</span>-->
<!--            </div>-->
<!--          </div>-->

<!--            <div class="my-5 range-wrapper d-flex justify-content-center flex-column align-items-center">-->
<!--                <div class="tooltip-container my-4">-->
<!--                    <div class="tooltip-custom">-->
<!--                      <input type="number" v-model="currentAmount" class="amount-input" @input="handleInputChange" />-->
<!--&lt;!&ndash;                        <app-typography variant="body1" color="#ffff" fontWeight="500">{{ currentAmount + `$` }}</app-typography>&ndash;&gt;-->
<!--                    </div>-->
<!--                </div>-->
<!--              <input type="range"  class="form-range" id="customRange1" v-model="currentAmount" min="10" max="10000">-->

<!--            </div>-->

          <div class="col-lg-6 mb-4 max-w-[612px]">
            <form-group-input
                type="text"
                label="Amount"
                placeholder="Enter Amount"
                v-model="currentAmount"
                :value="currentAmount"
                borderRadiusClass="border-sm"

            />
          </div>
          <div class="col-sm-12 mb-2 max-w-[590px]" v-if="selectedCapsuleIndex !== 0" >
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                   v-model="isEndDate" />
            <label class="form-check-label ml-3" for="flexCheckDefault">
              <app-typography variant="" >
                <!-- Using inertia-link for SPA navigation -->
                Add End Date
              </app-typography>
            </label>
          </div>
          <div class="row mb-4 w-full max-w-[612px]" v-if="selectedCapsuleIndex !== 0">

            <div class="col-lg-6 pr-[11px] my-3">
              <el-date-picker
                  v-model="start_date"
                  type="date"
                  placeholder="Select start date"
                  format="DD-MM-YYYY"
                  value-format="YYYY-MM-DD"
                  class="w-full custom-date-picker"
                  :clearable="true"
              />
            </div>
            <div class="col-lg-6 pr-[11px] my-3">
              <div v-if="isEndDate">
              <el-date-picker
                  v-model="end_date"
                  type="date"
                  placeholder="Select end date"
                  format="DD-MM-YYYY"
                  value-format="YYYY-MM-DD"
                  class="w-full custom-date-picker"
                  :clearable="true"
              />
              </div>
            </div>

          </div>
            <div class="capsule-wrapper" v-for="(item, index) in capsulesArray" :key="index" @click="selectCapsule(index)">
                <capsule-field capHeight="71px" :borderColor="selectedCapsuleIndex === index" :isCompleted="selectedCapsuleIndex === index"  capRadius="7px" capPadding="15px" :label="item.title"
                    :image-source="item.icon" :width="41" :height="41" />

            </div>
        </div>
      <div class="col-lg-12">
        <common-footer @back="handleBackClick" @continue="handleContinueClick" />
      </div>
    </div>

</template>
<script>
import { AppTypography, CapsuleField , CommonFooter, FormGroupInput } from "@/components/index";
export default {
    components: {
        AppTypography,
        CapsuleField,
      CommonFooter,
      FormGroupInput
    },
    props: {
        title: {
            type: String,
            default: 'Add funds to your wallet'
        },
        subTitle: {
            type: String,
            default: ''
        },
        totalAmount: {
            type: Number,
            default: 50
        },
      headingDetail: {
            type: Boolean,
            default: false,
        },
        capsulesArray: {
            type: Array,
            default: () => [
                { icon: 'clock-icon', title: 'One time' , value: 'one-off' },
                { icon: 'calandar-icon', title: 'Monthly' , value: 'monthly'},
                { icon: 'calandar-icon', title: 'Fortnight' , value: 'fortnightly'  },
                { icon: 'calandar-icon', title: 'Weekly' , value: 'weekly' },
            ]
        },
      paymentDetails: {
        type: Array,
        default: () => [
          { desc: "Harold is paying  [FREQUENCY]  his Medical Bills & Insurance premium", amount: "1000.00", disable: true },
          { desc: "Currently Harold has  amount of bills pending payment", amount: "250.00", disable: true },
          { desc: "Your Contribution [FREQUENCY]", amount: "50.00", disable: false }
        ],

      },
    },
  data() {
    return {
      selectedCapsuleIndex: 1,
      currentAmount: this.totalAmount,
      isSender: false,
      selectedCurrency: 'AUD',
      start_date:new Date().toISOString().split('T')[0],
      end_date:null,
      isEndDate: false,
    };
  },
  mounted() {
    const savedData = this.$store.getters.getFundDetail;
    if (savedData) {

      this.currentAmount = savedData.amount || this.totalAmount;
      this.selectedCurrency = savedData.currency || 'AUD';
      this.isSender = this.selectedCurrency === 'USD';
      const foundIndex = this.capsulesArray.findIndex(capsule => capsule.value === savedData.frequency);
      this.selectedCapsuleIndex = foundIndex !== -1 ? foundIndex : 1;
    }

  },
  computed: {
    updatedPaymentDetails() {
      const frequency = this.capsulesArray[this.selectedCapsuleIndex].title;
      return this.paymentDetails.map(item => ({
        ...item,
        desc: item.desc.replace('[FREQUENCY]', frequency)
      }));
    }
  },

  methods: {
    selectCapsule(index) {
      this.selectedCapsuleIndex = index;
      if (index === 0) {
        this.start_date = new Date().toISOString().split('T')[0];
      } else {
        this.start_date = null;
      }
    },
    handleBackClick() {
      this.$inertia.visit('/dashboard')
      // window.history.back()
    },
    handleContinueClick() {
      if (this.currentAmount < 10 || this.currentAmount > 10000) {
        this.$toast('Amount must be between $10 and $10,000.', 'error');
        return;
      }
      if (!this.start_date) {
        this.$toast('Start date is required', 'error');
        return;
      }
      if (this.isEndDate && !this.end_date) {
        this.$toast('End date is required', 'error');
        return;
      }

      if (this.selectedCapsuleIndex === null) {
        this.$toast('Please select a payment schedule to continue.', 'error');
      } else {

        const eventData = {

          amount: this.currentAmount,
          currency: this.selectedCurrency,
          start_date: this.start_date,
          frequency: this.capsulesArray[this.selectedCapsuleIndex].value,
          frequencyDisplay: this.capsulesArray[this.selectedCapsuleIndex].title
        };
        this.$emit('continue', eventData);
      }
    },

    toggleRole(role) {
      if (this.isSender && role === 'AUD') {
        this.isSender = false;
        this.selectedCurrency = 'AUD';
      } else if (!this.isSender && role === 'USD') {
        this.isSender = true;
        this.selectedCurrency = 'USD';
      }
    },
    handleInputChange(event) {
      const value = event.target.value;

      if (value >= 10 && value <= 10000) {
        this.currentAmount = value;
      } else {

        this.$toast('Please enter a value between 10 and 10000.', 'error');
      }
    }
  },
}
</script>
<style scoped>
.card {
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
    border-radius: 6px;
}

.card-header {
    padding: 40px 0px
}

.range-wrapper {
    min-width: 325px;
}

.capsule-wrapper {
    width: 592px
}

.tooltip-container {
    position: relative;
    display: inline-block;
    cursor: pointer;
    font-size: 16px;
}

.tooltip-custom {
    background-color: #3A84C6;
    padding: 15px 28px;
    border-radius: 50px;
    position: absolute;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;

}

.tooltip-custom::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #3A84C6 transparent transparent transparent;
}
.amount {
  height: 34px;
  border-radius: 8px;
  border: 1px solid #BEBEBE;
  padding: 6px 28px;
  width: 111px;

}
.toggle-container {
  display: flex;
  align-items: center;
  border-radius: 40px;
  border: 1px solid #d1d1d1;
  position: relative;
  width: 200px;
  height: 45px;
}

.toggle-button {
  width: 100px;
  height: 39px;
  background-color: #4682BE;
  border-radius: 18px;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: left 0.3s;
}

.toggle-button.active {
  left: calc(100% - 102px); /* Adjust based on the button width minus 2px for the border */
}

.toggle-knob {
  display: none; /* Hide the inner knob */
}

.role-labels {
  width: 100%;
  display: flex;
  justify-content: space-around;
}

.role-labels span {
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  z-index: 99;
  user-select: none; /* Prevent text selection */
}

.role-labels span.active {
  color: #fff  /* Text color for active state */
}

.role-labels span:not(.active) {
  color: #4682BE; /* Text color for inactive state */
}
.tooltip-custom {
  background-color: #3A84C6;
  padding: 10px 15px;
  border-radius: 50px;
  position: absolute;
  bottom: 125%;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1;
}

.tooltip-custom input.amount-input {
  background: none;
  border: none;
  color: white;
  font-size: 16px;
  text-align: center;
  width: 60px;
}

.tooltip-custom input.amount-input:focus {
  outline: none;
}
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>