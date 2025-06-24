<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div v-if="isSummaryDetails">
      <bill-summary />
      <common-footer :isContinueBtn="false" @back="handleBackClick" />
    </div>
    <div v-else class="container-fluid">
      <div class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar
              textWeight="600"
              textVariant="text-38"
              title="Transaction History"
              :isSubTitle="false"
              :isUserName="false"
              :isDivider="true"
              @tertiary-click="handleFriendsAndFamily"
              :isTertiaryBtn="true"
              :isActionButtons="true"
              tertiaryButtonText="Friends & Family"

          />
        </div>
        <div class="col-lg-12">
          <common-table
              :data="formattedTransactions"
              :header="tableColumns"
              :isActionHeader="false"
              :filterIcon="false">

<!--            <template v-slot:action="slotProps">-->

<!--            </template>-->
          </common-table>
        </div>
      </div>
    </div>
  </auth-layout>
</template>

<script>
import {
  TitleActionBar, CommonTable, CommonFooter
} from "@/components/index";
import BillSummary from "./BillSummary.vue"

export default {
  components: {
    TitleActionBar,
    CommonTable,
    BillSummary,
    CommonFooter
  },
  data() {
    return {
      isSummaryDetails: false,
      transactions: [],
      tableColumns: [
        { label: 'Transaction ID', field: 'id',filter: true },
        { label: 'Type', field: 'type', filter: true  },
        { label: 'Currency', field: 'currency', filter: true },
        { label: 'Amount', field: 'amount' },
        { label: 'Status', field: 'status',filter: true },
        { label: 'Sender Amount', field: 'senderAmount' },
        { label: 'Date', field: 'created_at',filter: true },
        { label: 'Action', field: 'action' },
      ],
      loading: false,
    };
  },
  mounted() {
    this.getTransactionHistory();
  },
  computed: {
    formattedTransactions() {
      return this.transactions.map(transaction => ({
        id: transaction.transaction_id,
        type: transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1),
        currency: transaction.currency,
        amount: `${transaction.currency} ${parseFloat(transaction.amount).toFixed(2)}`,
        status: transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1),
        senderAmount: `${transaction.sender_currency} ${parseFloat(transaction.sender_amount).toFixed(2)}`,
        created_at: new Date(transaction.created_at).toLocaleDateString(),
        action: {
          type: 'button',
          label: 'Detail',
          onClick: () => this.handleDetailButtonClick(transaction)
        }
      }));
    },

  },
  methods: {
    handleDetailButtonClick(transaction) {
      console.log('Detail button clicked for:', transaction.id);
      this.isSummaryDetails = true;
      // You might want to do something with the transaction data, like showing more details
    },

    handleBackClick() {
      this.isSummaryDetails = false;
    },
    handleFriendsAndFamily() {
      this.$inertia.visit('/friend-and-family')
    },
    getTransactionHistory() {
      this.loading = true;
      this.$inertia.get('/transactions-history', {}, {
        preserveState: true,
        onSuccess: (page) => {
          this.transactions = page.props.transactions;
          this.loading = false
        },
        onError: (error) => {
          this.loading = false
          console.error('Failed to fetch transactions:', error);
        }
      });
    }
  }
}
</script>
<style scoped></style>