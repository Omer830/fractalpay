<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
  <div class="container-fluid mb-4">
        <div class="row gy-3">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
          <div   class="col-lg-12">
<!--            <div>-->
<!--     -->
<!--              <h2 class="mb-4" :style="{color: tableHeadDetails.textColor}">-->
<!--                {{ organization.organisation }}-->
<!--              </h2>-->
<!--            </div>-->

            <div v-for="visit in billsDetails" :key="visit.id" class="mb-4">
              <common-table
                  :data="formatVisitData(visit.bills , visit.stats)"
                  :header="visitColumns"
                  :filterIcon="false"
                  :isActionHeader="true"
                  :tableHeading="visit.visit_reason"
                  :headingColor="tableHeadDetails.textColor"
                  :headingVariant="tableHeadDetails.textVariant"
                  :isTotalFooter="true"
                  :isPriceTotalFooter="visit.stats.totalBillAmount"
                  :totalText="'Total for ' + visit.visit_reason"
                  :paginationStatus="false"
                  :isSelectable="true"
                  tableHeight="auto"
                  :is_contributor="showIncoming"
                  @contributor-click="addContributor"
                  @selection-changed="handleSelectionChanged"
              >
                <template v-slot:action="slotProps">
                  <button
                      v-if="slotProps.row.billDocument"
                      @click="openDocument(slotProps.row.billDocument)"
                      class="btn btn-sm "
                  >
                    View Doc
                  </button>
                </template>

              </common-table>
            </div>
          </div>
            <div class="col-lg-12">
                <common-footer @continue="handleContinue" :isBackBtn="false" />
            </div>
        </div>
    <div v-if="showPdfModal" class="modal-overlay">
      <div class="modal-content">
        <button @click="showPdfModal = false" class="close-btn">×</button>
        <iframe :src="currentPdfUrl" width="100%" height="600px" />
      </div>
    </div>
    <contributors-list-modal
        title="Select Contributors"
        :subTitle="subTitle"
        :form-data="formData"
        :is-visible="contributorsModalStatus"
        :visit_id="this.formContributors.visit_id"
        @FORM_SUBMIT="handleContributorsSubmit"
        :contributorsArray="contributorsList"
        v-model:is-visible="contributorsModalStatus"
    />
  </div>
  </auth-layout>
</template>

<script>
import { TitleActionBar, CommonTable, CommonFooter } from "@/components/index";
import ContributorsListModal from "@/components/Modal/ContributorsListModal.vue";
export default {
    components: {
        TitleActionBar,
        CommonTable,
        CommonFooter,
      ContributorsListModal
    },
    data() {
        return {
            header: {
                title: "Payment Bill Summary",
                subTitle: "Please verify following information is correct before proceeding"
            },
          subTitle: 'Kindly fill in the visit information, amount, and due date sections to facilitate effective record-keeping.',

          tableHeadDetails: {
                textColor: '#3A84C6',
                textVariant: 'h2'
            },
          loading: false,
          showIncoming: true,
          selectedBillIds:[],
          visitColumns: [
            { label: 'User', field: 'user' },
            { label: 'Category', field: 'category' },
            { label: 'Total', field: 'total' },
            { label: 'Paid', field: 'paid' },
            { label: 'Balance', field: 'balance' },

            {label: 'Payable amount', field: 'payableAmount'},
            { label: 'Due Date', field: 'due_date' },

            {label: 'Action', field: 'action'}
          ],
          contributorsList:[],
          formData: this.$inertia.form({
            visit_reason: "",
            visit_details: "",
            name: "",
            provider_number: '',
            organisation: "",
            visit_type: "",
            contributorsIds: [],

          }),
          contributorsModalStatus: false,
          formContributors: this.$inertia.form({
            bill_id: null,
            contributorsIds: [],

          }),
            summaryData: [
                { billCategory: 'Pain ', amount: "$750" },
                { billCategory: 'Pain ', amount: "$750" },
                { billCategory: 'Pain ', amount: "$750" },
                { billCategory: 'Pain ', amount: "$750" },
                { billCategory: 'Pain ', amount: "$750" },
                { billCategory: 'Pain ', amount: "$750" },
            ],
            summaryColumns: [
                { label: 'Bill Category', field: 'billCategory' },
                { label: 'Amount', field: 'amount' },
            ],
          billsDetails:null,
          totalPayableAmount:null,
          showPdfModal: false,
          currentPdfUrl: null,
        }
    },
    mounted() {

      const idsParam = this.$store.getters.getVisitIds;

      if (idsParam) {
        try {
          this.bills = JSON.parse(idsParam);
        } catch (error) {
          console.error("Failed to parse 'ids' parameter:", error);
          this.bills = [];
        }

        this.fetchBillDetails(this.bills);
      } else {
        console.warn("No 'ids' parameter found in the URL.");
      }
  },
  computed:{
    visitColumns(){
      const columns = [
        { label: 'User', field: 'user' },
        { label: 'Category', field: 'category' , filter: true },
        { label: 'Total', field: 'total' },
        { label: 'Paid', field: 'paid' },
        { label: 'Balance', field: 'balance' },
        { label: 'Invoice Number', field: 'invoice_number' },
        {label: 'Contributors', field: 'contributors'},
        {label: 'Payable amount', field: 'payableAmount'},
        { label: 'Due Date', field: 'due_date' },

        {label: 'Action', field: 'action'}
      ]

      return columns;
    }
  },
    methods: {
      handleContinue() {
        if (!this.selectedBillIds || this.selectedBillIds.length === 0) {
          this.$toast("Please select at least one bill to proceed with payment.", "error");
          return;
        }
        this.$inertia.visit('/add-bill');
      },
      fetchBillDetails() {
          this.loading = true;
        if (this.bills && this.bills.length > 0) {
          this.$inertia.post('/payment-bill-summary', { visits: this.bills }, {
            onSuccess: (page) => {
              this.billsDetails = page.props.billsDetails.data;
              this.contributorsList = page.props.contributorsList.data
              // if(page.props.billsDetails.data?.owner){
              //   this.billsDetails = page.props.billsDetails?.data?.owner;
              // }else {
              //   this.billsDetails = page.props.billsDetails?.data?.contributor;
              // }

              // let totalPayable = 0;
              // this.billsDetails.forEach(organization => {
              //   organization.visits.forEach(visit => {
              //     totalPayable += visit.stats.totalBillAmount;
              //   });
              // });
              // this.totalPayableAmount = totalPayable;

              this.totalPayableAmount = this.billsDetails.reduce((total, visit) => {
                return total + (visit.stats?.totalBillAmount || 0);
              }, 0);
              this.$store.commit('SET_BILL_PAYABLE_AMOUNT',this.totalPayableAmount )
              this.loading = false;
            },
            onError: (errors) => {
              this.showErrors(errors);
              this.loading = false;
              console.error("An error occurred while submitting the bills:", errors);

            }
          });
        } else {
          alert("Please select at least one bill to proceed with payment.");
        }
      },
      showErrors(errors) {
        if (errors && Object.keys(errors).length > 0) {
          Object.keys(errors).forEach(key => {
            this.$toast(`Error: ${errors[key]}`, 'error');
          });
        }
      },
      formatVisitData(bills, visitStats) {
        return bills.map(bill => ({
          id: bill.id,
          user: bill.user,
          category: bill.category,
          total: bill.total,
          paid: bill.paid,
          balance: bill.balance,
          invoice_number: bill.invoice_number,
          due_date: bill.due_date,
          billDocument: bill.billDocument,
          contributors: {
            count: bill.contributors.length,
            details: bill.contributors,
            type: 'contributors'
          },
          payableAmount: {
            amount: visitStats?.total,
            percentage: visitStats?.paid_percentage,
            type: 'progress',
            progressClass: this.getProgressClass(visitStats?.paid_percentage)
          }
        }));
      },

      handleSelectionChanged(selectedIds) {
        console.log("Selected IDs:", JSON.stringify(selectedIds));
        this.selectedBillIds = selectedIds;
        this.$store.commit('SET_Bill_IDS', this.selectedBillIds);

      },
      openDocument(url) {
        this.currentPdfUrl = url;
        this.showPdfModal = true;
      },
      getProgressClass(percentage) {
        if (percentage >= 76 && percentage <= 100) return '#70C043';
        if (percentage >= 51 && percentage <= 75) return '#4682BE';
        if (percentage >= 21 && percentage <= 50) return '#FB762B';
        if (percentage >= 0 && percentage <= 20) return '#D40D0D';
        return '#DC3545';
      },
      handleContributorsSubmit(selectedIds) {
        this.formContributors.contributorsIds = selectedIds.contributorsIds
        this.handleContinueContributor()
        // this.contributorsModalStatus = false

      },
      handleContinueContributor() {

        this.loading = true;

        this.formContributors.post('/add-contributors-bill', {
          onFinish: () => this.formData.reset('loading'),

          onSuccess: (response) => {
            this.$toast('Contributors added successfully', 'success');
            this.$inertia.visit('/payment-bill-summary')
            this.loading = false;
            this.contributorsModalStatus = false
            if (response.props.success) {


            }
          },
          onError: (error) => {
            this.showErrors(error);
            this.loading = false;
          }
        });
      },
      addContributor(row) {
        this.contributorsModalStatus = true
        this.formContributors.bill_id = row.id
        console.log('Adding contributor to:', row.id);
      },

    },
}
</script>

<style scoped>

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.modal-content {
  background: #fff;
  width: 80%;
  max-width: 90%;
  border-radius: 10px;
  overflow: hidden;
  position: relative;
}
.close-btn {
  position: absolute;
  right: 10px;
  top: 10px;
  background: red;
  color: white;
  border: none;
  font-size: 20px;
  padding: 0 10px;
  cursor: pointer;
  border-radius: 50%;
}

</style>