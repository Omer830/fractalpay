<template>
  <div class="container-fluid">
    <div class="row gy-4">
      <div class="col-lg-12">
        <title-action-bar :title="header.title" :isSubTitle="false" :isActionButtons="true" btnRound="md"
                          :isPrimaryBtn="selectedBillIds.length > 0" @primary-click="handlePayNow"
                          :isSecondaryBtn="true" :isUserName="false" :isDivider="true"
                          textVariant="text-38" textWeight="600" primaryButtonText="Pay Now"
                          :secondaryButtonText="header.secBtnText" :buttonArray="buttonArray"
                          @secondary-click="handleSecondaryBtn" @TOGGLE_BUTTON_CLICK="toggleData"/>
      </div>

      <div class="details-wrapper d-flex gap-5 overflow-auto">
        <div class="details-card pointer overflow-auto">
          <expense-tracker-card title="Total Payable" :amount="expenseStats.total"/>
        </div>
        <div class="details-card pointer overflow-auto">
          <expense-tracker-card title="Due This Week" :amount="expenseStats.due_this_week"/>
        </div>
        <div class="details-card pointer overflow-auto">
          <expense-tracker-card title="Overdue Bills" :amount="expenseStats.over_due_bills"/>
        </div>
        <div class="details-card pointer overflow-auto" v-for="(category, index) in expenseStats.categories"
             :key="index">
          <expense-tracker-card :title="category.name" :amount="category.value"/>
        </div>
      </div>
      <div class="col-lg-12 d-flex flex-column gap-3 mb-4">
        <common-table :data="tableData"
                      :header="tableColumns"
                      :isActionHeader="true"
                      @selection-changed="handleSelectionChanged"
                      @contributor-click="addContributor"
                      :is_contributor="showIncoming"
                      tableHeading="All Visits"
                      :isSelectable="true"
                      :filterButtonArray="filterButtonArray"
        >
          <template v-slot:action="slotProps">
            <el-dropdown>
                  <span class="el-dropdown-link">
                    <image-svg name="cricle-with-3dots" width="30px" height="40px"></image-svg>
                  </span>
              <template #dropdown>
                <el-dropdown-menu>
                  <!--                          <el-dropdown-item >View</el-dropdown-item>-->
                  <!--                          <el-dropdown-item divided>Edit</el-dropdown-item>-->
                  <el-dropdown-item @click="addBillPayRed">Add Bill</el-dropdown-item>
                  <el-dropdown-item divided @click="SingleBillDetail(slotProps.row)">View Bill(s)</el-dropdown-item>
<!--                  <el-dropdown-item divided @click="handlePayNow(slotProps.row)">Pay Now</el-dropdown-item>-->
                  <el-dropdown-item divided @click="deleteVisit(slotProps.row.id)">Delete Bill</el-dropdown-item>

                </el-dropdown-menu>
              </template>
            </el-dropdown>
          </template>
        </common-table>
      </div>
      <div class="col-lg-12" v-if="selectedBillIds.length > 0">
        <common-footer :isBackBtn="false" buttonTitle="Pay now" @continue="handlePayNow"/>
      </div>
    </div>
    <add-new-visit title="Add New Visit"
                   :subTitle="subTitle"
                   :form-data="formData"
                   :is-visible="modalStatus"
                   @FORM_SUBMIT="handleFormSubmit"
                   v-model:is-visible="modalStatus"
                   :visitType="visitType"
                   :contributorsArray="contributorsList"
    ></add-new-visit>
    <contributors-list-modal
        title="Select Contributors"
        :subTitle="subTitle"
        :form-data="formData"
        :is-visible="contributorsModalStatus"
        :visitType="visitType"
        :visit_id="this.formContributors.visit_id"
        @FORM_SUBMIT="handleContributorsSubmit"
        :contributorsArray="contributorsList"
        v-model:is-visible="contributorsModalStatus"
    />
  </div>
</template>

<script>
import {TitleActionBar, ExpenseTrackerCard, CommonTable, ImageSvg, CommonFooter} from "@/components/index.js";
import AddNewVisit from "@/components/Modal/AddNewVisit.vue";
import ContributorsList from "@/components/Modal/ContributorsListModal.vue";
import ContributorsListModal from "@/components/Modal/ContributorsListModal.vue";
import Swal from "sweetalert2";

export default {
  components: {
    ContributorsListModal,
    ExpenseTrackerCard,
    CommonTable,
    TitleActionBar,
    ImageSvg,
    CommonFooter,
    AddNewVisit,
    ContributorsList
  },
  props: {
    billList: {
      type: Array,
      default: []
    },
    contributorsList: {
      type: Array,
      default: []
    },
    visitType: {
      type: Array,
      default: []
    },
    expenseStats: {
      type: Object,
      default: {}
    }
  },
  data() {
    return {
      formData: this.$inertia.form({
        visit_reason: "",
        visit_details: "",
        name: "",
        provider_number: '',
        organisation: "",
        visit_type: "",
        contributorsIds: [],

      }),
      buttonArray: [
        { label: "My Visits", textColor: "#4682BE", outline: true },
        { label: "Share with me", textColor: "#4682BE", outline: true },
        { label: "Contribution", textColor: "#4682BE", outline: true },

      ],

      modalStatus: false,
      showIncoming: true,
      contributorsModalStatus: false,
      formContributors: this.$inertia.form({
        visit_id: null,
        contributorsIds: [],

      }),

      subTitle: 'Kindly fill in the visit information, amount, and due date sections to facilitate effective record-keeping.',
      header: {
        title: "Expense Tracker",
        secBtnText: "Add Visit"
      },
      expensCardArray: [
        {title: 'Total Payable', amount: '480'},
        {title: 'Due This Week', amount: '480'},
        {title: 'Overdue Bills', amount: '480'},
        {title: 'Medicines', amount: '480'},
        {title: 'Hospital Bills', amount: '480'},
        {title: 'Hospital Bills 3', amount: '480'},
      ],
      tableData: [],
      tableColumns: [
        {label: 'Hospital', field: 'hospital', filter: true},
        {label: 'Visit Name', field: 'visitName', filter: true},
        {label: 'Visit Type', field: 'visitType'},
        {label: 'Name', field: 'doctorName'},
        {label: 'Contributors', field: 'contributors'},
        {label: 'Payable amount', field: 'payableAmount'},
        {label: 'Action', field: 'action'}
      ],
      selectedBillIds: [],
    }
  },
  mounted() {

    // Check the expense type and auto-select "Share with me" if applicable

  },
  watch: {
    '$store.getters.getExpenseType': {
      handler(newValue) {
        if (newValue === 'Share with me') {
          this.toggleData({ label: 'Share with me' });
        }
      },
      immediate: true // Mounted pe bhi call ho jayega
    }
  },
  computed: {
    tableColumns() {
      const columns = [
        { label: 'Hospital', field: 'hospital', filter: true },
        { label: 'Visit Name', field: 'visitName', filter: true },
        { label: 'Visit Type', field: 'visitType' },
        { label: 'Name', field: 'doctorName' },
        { label: 'Payable amount', field: 'payableAmount' },
        { label: 'Action', field: 'action' }
      ];

      if (this.showIncoming) {
        columns.splice(4, 0, { label: 'Contributors', field: 'contributors' }); // Insert at correct position
      }

      return columns;
    },
    tableData() {
      return this.billList.map(value => {

        const contributors = value.contributors.map(contributor => ({
          id: contributor.id,
          name: contributor.full_name
        }));

        return {
          id: value.id,
          hospital: value.organisation,
          visitName: value.visit_reason,
          visitType: value.visit_type,
          doctorName: value.name,
          contributors: {
            count: contributors.length,
            details: contributors,
            type: 'contributors'
          },
          payableAmount: {
            amount: value.stats?.total,
            percentage: value.stats?.paid_percentage,
            type: 'progress',
            progressClass: this.getProgressClass(value?.stats?.paid_percentage)
          }
        };
      });
    },

  },
  methods: {
    handleDropdownClick() {
      alert('clicked')
    },
    handleSecondaryBtn() {
      console.log('hit')
      this.modalStatus = true;
      // this.resetForm()
      // this.$inertia.visit('/add-manual-bills')
    },
    resetForm() {
      console.log(this.formData.contributorsIds , 'hello zain')
      this.formData = this.$inertia.form({
        visit_reason: "",
        visit_details: "",
        name: "",
        provider_number: '',
        organisation: "",
        visit_type: "",
        contributorsIds: [],
      });
    },
    totalPayable() {
      this.$inertia.visit('total-payable')
    },
    payNow() {
      this.$inertia.visit('total-payable')
    },
    getProgressClass(percentage) {
      if (percentage >= 76 && percentage <= 100) return '#70C043';
      if (percentage >= 51 && percentage <= 75) return '#4682BE';
      if (percentage >= 21 && percentage <= 50) return '#FB762B';
      if (percentage >= 0 && percentage <= 20) return '#D40D0D';
      return '#DC3545';
    },
    addContributor(row) {
      this.contributorsModalStatus = true
      this.formContributors.visit_id = row.id
      console.log('Adding contributor to:', row.id);
    },
    SingleBillDetail(id) {
      this.selectedBillIds.push(id.id)
      if (this.selectedBillIds && this.selectedBillIds.length > 0) {
        const ids = JSON.stringify(this.selectedBillIds);
        this.$inertia.visit(`/payment-bill-summary`);
        this.$store.commit('SET_VISIT_IDS', ids)
      } else {
        this.$toast("Please select at least one bill to proceed with payment.", 'error');
      }
    },
    addBillPayRed() {
      this.$inertia.visit('/add-manual-bills')
    },
    payBill() {
      this.$inertia.visit('/payment-bill-summary')
    },
    handleSelectionChanged(selectedIds) {
      console.log("Selected IDs:", JSON.stringify(selectedIds));
      this.selectedBillIds = selectedIds;
    },
    handleContributorsSubmit(selectedIds) {
      this.formContributors.contributorsIds = selectedIds.contributorsIds
      this.handleContinue()
      // this.contributorsModalStatus = false

    },
    handleInvite() {
      this.$inertia.visit('/expense-tracker')
    },
    handleContinue() {

      this.loading = true;

      this.formContributors.post('/add-contributors', {
        onFinish: () => this.formData.reset('loading'),

        onSuccess: (response) => {
          this.$toast('Contributors added successfully', 'success');
          this.$inertia.visit('/expense-tracker')
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

    deleteVisit(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this Visit?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4682BE',
        cancelButtonColor: '#bcbcbc',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;

          this.$inertia.post('/delete-visit', { visit_id: id }, {
            onFinish: () => this.formData.reset('loading'),
            onSuccess: (response) => {
              this.$toast('Bill deleted successfully', 'success');
              this.$inertia.visit('/expense-tracker');
              this.loading = false;
              this.contributorsModalStatus = false;
              if (response.props.success) {
                // Handle any additional success logic
              }
            },
            onError: (error) => {
              this.showErrors(error);
              this.loading = false;
            }
          });
        }
      });
    },

    handlePayNow() {
      if (this.selectedBillIds && this.selectedBillIds.length > 0) {
        const ids = JSON.stringify(this.selectedBillIds);
        this.$inertia.visit(`/add-bill`);
        this.$store.commit('SET_Bill_IDS', ids)
      } else {
        this.$toast("Please select at least one bill to proceed with payment.", 'error');
      }
    },
    handleFormSubmit(form) {
      console.log(JSON.stringify(this.formData))
      if (!this.formData.visit_reason) {
        this.$toast('The visit reason field is required', 'error');
        return;
      }

      if (!this.formData.provider_number) {
        this.$toast('The provider Number field is required', 'error');
        return;
      }

      if (!this.formData.organisation) {
        this.$toast('The organisation ID field is required', 'error');
        return;
      }

      if (!this.formData.visit_type) {
        this.$toast('The visit type ID field is required', 'error');
        return;
      }
      this.loading = true;
      this.formData = form;

      this.formData.post('/add-visit', {
        onFinish: () => this.formData.reset('loading'),

        onSuccess: (response) => {
          this.$toast('Visit added successfully', 'success');
          this.$inertia.visit('/expense-tracker');
          this.loading = false;
          this.modalStatus = false
          if (response.props.success) {


          }
        },
        onError: (error) => {
          this.showErrors(error);
          this.loading = false;
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
    toggleData(isIncoming) {

      if (isIncoming.label === 'Share with me'){
        this.showIncoming = false
      }else {
        this.showIncoming = true
      }

      this.$emit('assigndBill', isIncoming);
    },
  }
}
</script>

<style scoped>
.search {
  border: 1px solid #4682BE;
  border-radius: 8px;
}

.input-group-btn {
  border-radius: 8px;
}

.details-wrapper {
  width: 99%;
  overflow-x: scroll;
}

.details-card {
  min-width: 222px
}

/* Scroll bar stylings */
::-webkit-scrollbar {
  display: none;
}

.toggle-container {
  display: flex;
  align-items: center;
  border-radius: 40px;
  border: 1px solid #d1d1d1;
  position: relative;
  width: 200px;
  height: 45px;
  background-color: #f8f9fa;
  overflow: hidden;
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

.role-labels {
  width: 100%;
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  font-weight: bold;
  color: #4682BE;;
  z-index: 1;
  position: relative;
}

.role-labels span {
  cursor: pointer;
  flex: 1;
  text-align: center;
  z-index: 2;
  transition: color 0.3s ease-in-out;
}

.role-labels span.active {
  color: #fff;
}


</style>