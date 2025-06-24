<<template>
  <div class="container mt-5">
    <div class="card p-4">
      <!-- Ensure transactionDetails is available before accessing its first item -->
      <div v-if="transactionDetails?.length > 0" class="d-flex align-items-center mb-4">
        <img
            src="https://via.placeholder.com/50"
            alt="User Avatar"
            class="rounded-circle me-3"
        />
        <div>
          <h5 class="mb-0">{{ transactionDetails[0]?.user?.name }}</h5>
        </div>
      </div>

      <div v-if="transactionDetails?.length > 0" class="row mb-4 user-info-row">
        <div class="col-2 user-info-item">
          <small class="text-muted">Type</small>
          <p class="mb-0">{{ transactionDetails[0]?.type }}</p>
        </div>
        <div class="col-2 user-info-item">
          <small class="text-muted">Start Date</small>
          <p class="mb-0">{{ transactionDetails[0]?.details?.start_date }}</p>
        </div>
        <div class="col-2 user-info-item">
          <small class="text-muted">End Date</small>
          <p class="mb-0">{{ transactionDetails[0]?.details?.end_date }}</p>
        </div>
        <div class="col-2 user-info-item">
          <small class="text-muted">Status</small>
          <p class="text-success mb-0">{{ transactionDetails[0]?.status }}</p>
        </div>
        <div class="col-2 user-info-item">
          <small class="text-muted">Amount</small>
          <p class="mb-0">${{ transactionDetails[0]?.amount }}</p>
        </div>

      </div>

      <hr />
<div class="flex justify-between">
      <h6 class="mb-3">Recent Transactions No  ({{ transactionDetails?.length }})</h6>
  <div>
<!--    <h6 class="mb-3">Total ({{totalAmount}})</h6>-->
  </div>
</div>
      <table class="table">
        <tbody>
        <!-- Ensure remainingTransactions is not empty before rendering rows -->
        <tr v-if="remainingTransactions.length > 0" v-for="(transaction, index) in remainingTransactions" :key="index">
          <td class="d-flex align-items-center">
            <img
                src="https://via.placeholder.com/40"
                alt="User Avatar"
                class="rounded-circle me-3"
            />
            <div>
              <p class="mb-0">{{ transaction.user?.name }}</p>
            </div>
          </td>
          <td>{{ transaction.type }}</td>
          <td>{{ transaction.details?.start_date }}</td>
          <td>
            <span class="text-success">{{ transaction.status }}</span>
          </td>
          <td>${{ transaction.amount }}</td>
        </tr>
        <!-- If no data, show this message -->
        <tr v-if="remainingTransactions.length === 0">
          <td colspan="5" class="text-center">No more transactions available</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    transactionDetails: {
      type: Array,
      required: true,
      default: () => []
    },
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    remainingTransactions() {
      return this.transactionDetails?.length > 1 ? this.transactionDetails.slice(1) : [];
    },


  }
};
</script>


<style scoped>
.card {
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.table td {
  vertical-align: middle;
}

.table img {
  width: 40px;
  height: 40px;
}

hr {
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

h6 {
  font-weight: bold;
}

.user-info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.user-info-item {
  position: relative;
  text-align: center;
  padding: 0 15px;
}

.user-info-item::after {
  content: '';
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  height: 60%;
  width: 1px;
  background-color: rgba(0, 0, 0, 0.1);
}

.user-info-item:last-child::after {
  right: 50%;
}
</style>
