<template>
  <div class="wallet-container">
    <div class="card">
      <app-typography variant="body1" fontWeight="600">{{ mainTitle }}</app-typography>
      <div v-if="!noDataFound" class="card-body d-flex gap-5">
        <div class="inner-card" v-for="(item, index) in cardsData" :key="index">
          <div class="header d-flex justify-content-between align-items-center mb-2">
            <app-typography variant="body1" color="#4682BE" fontWeight="600">
              {{ item.title }}
            </app-typography>
            <image-svg v-if="item.icon" width="16px" height="16px" :name="item.icon" />
          </div>

          <!-- Buttons Group -->
          <div class="buttons d-flex gap-3 my-3" v-if="item.buttonsGroup">
            <div
                v-for="(btn, btnIndex) in item.buttonsGroup"
                :key="btnIndex"
                @click="selectedPeriod = btn.toLowerCase().replace(' ', '')"
            >
              <reuseable-button
                  :class="['btn-group', { active: selectedPeriod === btn.toLowerCase().replace(' ', '') }]"
                  fontSize="8px"
                  round="sm"
                  btnHeight="23px"
              >
                {{ btn }}
              </reuseable-button>
            </div>
          </div>


          <hr />

          <!-- Payment Details -->
          <div v-if="item.paymentDetails" class="typography-group d-flex flex-wrap gap-3 mt-3">
            <div v-for="(typo, typoIndex) in item.paymentDetails" :key="typoIndex">
              <div class="d-flex">
                <div :style="{ backgroundColor: typo.bullet }" class="bullet me-1"></div>
                <app-typography variant="text-7" color="#777E90" fontWeight="600">
                  {{ typo.title }}:
                </app-typography>
                <app-typography variant="text-7" color="#152C5B" fontWeight="600" class="ms-1">
                  {{ typo.amount }}
                </app-typography>
              </div>
            </div>
          </div>

          <!-- Paid Bill Details -->
          <div v-if="item.paidBillDetails" class="bill-wrapper mt-3">
            <div v-for="(e, i) in item.paidBillDetails" :key="i" class="d-flex justify-content-between mb-2">
              <app-typography variant="body3" color="#565656" fontWeight="600">
                {{ e.title }}
              </app-typography>
              <div class="d-flex gap-2 payed-bills">
                <div :style="{ backgroundColor: e.bullet }" class="bullet"></div>
                <app-typography variant="text-7" color="#777E90" fontWeight="600">
                  {{ e.status }}
                </app-typography>
                <app-typography variant="text-7" color="#152C5B" fontWeight="600">
                  {{ e.amount }}
                </app-typography>
              </div>
            </div>
          </div>

          <!-- Pending Bill Details -->
          <div v-if="item.pendingBillDetails" class="bill-wrapper mt-3">
            <div v-for="(e, i) in item.pendingBillDetails" :key="i" class="d-flex justify-content-between mb-2">
              <app-typography variant="body3" color="#565656" fontWeight="600">
                {{ e.title }}
              </app-typography>
              <div class="d-flex gap-2 payed-bills">
                <div :style="{ backgroundColor: e.bullet }" class="bullet"></div>
                <app-typography variant="text-7" color="#777E90" fontWeight="600">
                  {{ e.status }}
                </app-typography>
                <app-typography variant="text-7" color="#152C5B" fontWeight="600">
                  {{ e.amount }}
                </app-typography>
              </div>
            </div>
          </div>


        </div>
      </div>

      <div v-else class="card-body no-data">
        <app-typography variant="body1" fontWeight="600">You don’t have any history here.</app-typography>
      </div>
    </div>
  </div>
</template>

<script>
import { AppTypography, ImageSvg, ReuseableButton } from "@/components/index";

export default {
  components: {
    AppTypography,
    ImageSvg,
    ReuseableButton
  },
  props: {
    mainTitle: {
      type: String,
      default: "Wallet History",
    },
    latestBills: {
      type: Object,
      required: true,
    },
    pendingBills: {
      type: Object,
      required: true,
    },
    walletHistory: {
      type: Object,
      required: true,
    },
    noDataFound: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      selectedPeriod: "1 Month", // Default selected period
    };
  },
  computed: {
    cardsData() {
      const periodData = this.walletHistory?.data?.periods?.[this.selectedPeriod] || {};

      return [
        {
          title: "Monthly, Weekly and Daily Analytics",
          buttonsGroup: ["1 Month", "1 Week", "1 Day"],
          paymentDetails: [
            {
              bullet: "#F59D31",
              title: "Receive",
              amount: periodData.receive ? `$${periodData.receive}` : "$0",
            },
            {
              bullet: "#E54B75",
              title: "Send",
              amount: periodData.send ? `$${periodData.send}` : "$0",
            },
            {
              bullet: "#FFD130",
              title: "Net Balance",
              amount: this.walletHistory?.data?.net_balance?.balance
                  ? `$${this.walletHistory.data.net_balance.balance}`
                  : "$0",
            },
          ],
        },
        {
          title: "Latest Bills (Paid)",
          icon: "payed-bills-icon",
          paidBillDetails: Array.isArray(this.latestBills?.data?.transactions?.send) && this.latestBills.data.transactions.send.length > 0
              ? this.latestBills.data.transactions.send.map((bill) => ({
                title: bill.bill?.description ?? "Unknown",
                status: "Paid",
                amount: `$${bill.amount}`,
                bullet: "#23C38E",
              }))
              : [
                {
                  title: "No recent bills",

                },
              ],
        },

        {
          title: "Bills Pending",
          icon: "payed-bills-icon",
          pendingBillDetails: Array.isArray(this.pendingBills?.data) && this.pendingBills.data.filter(bill => !bill.is_paid).length > 0
              ? this.pendingBills.data
                  .filter((bill) => !bill.is_paid)
                  .map((bill) => ({
                    title: bill.description ?? "Unknown",
                    status: "Pending",
                    amount: `$${bill.amount}`,
                    bullet: "#E54B75",
                  }))
              : [
                {
                  title: "No pending bills",

                },
              ],
        },

      ];
    },
  },
};
</script>

<style scoped>
.wallet-container {
  padding: 16px;
}

.card {
  padding: 16px 24px 0 24px;
  border-radius: 10px;
}

.card-body {
  min-height: 281px;
  overflow-x: auto;
}

.inner-card {
  flex: 1 1 calc(33.33% - 32px);
  padding: 24px 16px 48.54px 16px;
  border: 1px solid #DDDDDD;
  border-radius: 6px;
  min-width: 240px;
  height: 206px;
}

.payed-bills {
  width: 83px;
}

.no-data {
  display: grid;
  place-items: center;
}

.btn-group {
  background: #23C38E40;
  color: #3D3F65 !important;
  border-radius: 5px !important;
  font-weight: 700;
  width: 60px;
}

.bullet {
  border-radius: 50%;
  height: 7px;
  width: 7px;
}

.typography-group {
  min-width: 63px;
}

.bill-wrapper {
  border: 1px solid #BFBFBF;
  padding: 10px 9px;
  border-radius: 8px;
  overflow-y: auto;
  max-height: 117px;
  width: 100%;
}

/* Scroll styling */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: #3A84C6;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: #3A84C6;
}

/* Responsive */
@media (max-width: 992px) {
  .inner-card {
    flex: 1 1 calc(50% - 32px);
  }
}

@media (max-width: 768px) {
  .inner-card {
    flex: 1 1 100%;
  }
}
.btn-group.active {
  background-color: #4682BE;
  color: white !important;
}
</style>
