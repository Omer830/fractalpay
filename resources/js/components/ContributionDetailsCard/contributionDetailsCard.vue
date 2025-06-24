<template>
  <div>
    <div class="row" v-if="mappedCardDetails.length > 0">
      <div class="col-xl-6" v-for="(item, index) in mappedCardDetails" :key="index">
        <div class="card-wrapper d-flex gap-2">
          <div class="card d-flex justify-content-between flex-row flex-wrap w-75">
            <app-typography variant="h4" fontWeight="500" :color="item.cardColor">
              {{ item.title }}
            </app-typography>
            <app-typography variant="h5" fontWeight="600" :color="item.cardColor">
              {{ formatCurrency(item.amount) }}
            </app-typography>
          </div>
          <div class="card card-details w-25">
            <div
                class="details d-flex justify-content-between flex-wrap"
                v-for="(val, i) in item.frequency"
                :key="i"
            >
              <app-typography variant="body3" fontWeight="500" color="#4F4F4F">
                {{ val.heading }}
              </app-typography>
              <app-typography variant="body3" fontWeight="500" :color="item.cardColor">
                {{ formatCurrency(val.details) }}
              </app-typography>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="empty-state">
      <p>No contributions to display at the moment.</p>
    </div>
  </div>
</template>

<script>
import AppTypography from "../Typography/appTypography.vue";

export default {
  components: {
    AppTypography,
  },
  props: {
    commitments: {
      type: Object,
      default: () => ({
        incoming: {
          total_amount: 0,
          weekly_total: 0,
          fortnightly_total: 0,
          monthly_total: 0,
        },
        outgoing: {
          total_amount: 0,
          weekly_total: 0,
          fortnightly_total: 0,
          monthly_total: 0,
        },
      }),
    },
  },
  computed: {
    mappedCardDetails() {
      if (!this.commitments || !this.commitments.incoming || !this.commitments.outgoing) {
        return [];
      }
      return [
        {
          title: "Contribution IN",
          amount: this.commitments.incoming.total_amount || 0,
          cardColor: "#14AE5C",
          frequency: [
            { heading: "Weekly", details: this.commitments.incoming.weekly_total || 0 },
            { heading: "Fortnightly", details: this.commitments.incoming.fortnightly_total || 0 },
            { heading: "Monthly", details: this.commitments.incoming.monthly_total || 0 },
          ],
        },
        {
          title: "Contribution Out",
          amount: this.commitments.outgoing.total_amount || 0,
          cardColor: "#F24822",
          frequency: [
            { heading: "Weekly", details: this.commitments.outgoing.weekly_total || 0 },
            { heading: "Fortnightly", details: this.commitments.outgoing.fortnightly_total || 0 },
            { heading: "Monthly", details: this.commitments.outgoing.monthly_total || 0 },
          ],
        },
      ];
    },
  },
  methods: {
    formatCurrency(value) {
      const num = parseFloat(value);
      if (isNaN(num)) return "$0.00"; // Fallback for invalid numbers
      return `$${num.toFixed(2)}`;
    },
  },
};



</script>

<style scoped>
.card {
  box-shadow: 0px 0px 4px 0px #00000040;
  padding: 40px 20px;
  border-radius: 10px;
}

.card-details {
  padding: 12px 8px;
  gap: 20px;
}

.empty-state {
  text-align: center;
  margin: 20px 0;
  font-size: 18px;
  color: #6c757d;
}
</style>
