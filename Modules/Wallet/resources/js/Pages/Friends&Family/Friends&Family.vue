<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="container-fluid">
      <div class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar :title="header.title" :isSubTitle="false" isActionButtons="true" btnRound="md"
                            :isPrimaryBtn="false" :isSecondaryBtn="true" :isUserName="false" :isDivider="true"
                            textVariant="text-38" textWeight="600" secondaryButtonText="Setup Payment"
                            @secondary-click="handleSecondaryBtn" @TOGGLE_BUTTON_CLICK="toggleData" :buttonArray="buttonArray"/>
        </div>

        <div class="col-lg-12 flex justify-center mx-auto">
          <div class="card-body min-h-[60vh] px-0">

            <common-table :header="inTableColumns" :data="formattedTableData" :isActionHeader="false"
                          :filterIcon="false" headerBgColor="#ffff"
                          headerTextColor="#36454F">

              <template v-slot:action="slotProps">
                <el-dropdown>
                  <span class="el-dropdown-link">
                    <image-svg name="cricle-with-3dots" width="30px" height="40px"></image-svg>
                  </span>
                  <template #dropdown>
                    <el-dropdown-menu>
                      <el-dropdown-item @click="payNowClick(slotProps.row)">Pay Now</el-dropdown-item>
                      <el-dropdown-item divided @click="viewDetail(slotProps.row.user_id)">Detail</el-dropdown-item>
                      <el-dropdown-item divided @click="userUnFriend(slotProps.row.user_id)">Un Friend</el-dropdown-item>
                    </el-dropdown-menu>
                  </template>
                </el-dropdown>
              </template>

            </common-table>
          </div>
        </div>
        <div class="col-lg-12">
          <common-footer :isBackBtn="true" :isContinueBtn="false" buttonTitle="Back" @back="backButton()"/>
        </div>
      </div>
    </div>
  </auth-layout>
</template>

<script>
import {
  TitleActionBar,
  ExpenseTrackerCard,
  CommonTable,
  ImageSvg,
  CommonFooter,
  ReuseableButton
} from "@/components/index.js";
import Swal from "sweetalert2";

export default {
  components: {
    ExpenseTrackerCard,
    CommonTable,
    TitleActionBar,
    ImageSvg,
    CommonFooter,
    ReuseableButton,
  },
  data() {
    return {
      formData: {},
      modalStatus: false,
      subTitle: 'Kindly fill in the visit information, amount, and due date sections to facilitate effective record-keeping.',
      header: {
        title: "Friends & Family",
        secBtnText: "Add Visits"
      },
      inTableColumns: [
        {label: 'Name', field: 'name'},
        {label: 'Type', field: 'type', filter: true },
        {label: 'Date', field: 'date'},
        {label: 'Status', field: 'status'},
        {label: 'Amount', field: 'amount'},
        {label: 'Action', field: 'action'}
      ],
      contributorsList: {
        incoming: {
          commitments: []
        },
        outgoing: {
          commitments: []
        }
      },
      loading: false,
      showIncoming: 'Incoming',
      buttonArray: [
        // { label: "All", textColor: "#4682BE", outline: true },
        { label: "Incoming", textColor: "#4682BE", outline: true },
        { label: "Outgoing", textColor: "#4682BE", outline: true },

      ]
    };
  },
  mounted() {
    this.getUserList();
  },
  computed: {
    formattedTableData() {
      let listToDisplay = [];

      // Check if contributorsList and the specific category are not null or undefined
      if (this.showIncoming === 'All' && this.contributorsList && this.contributorsList.all && Array.isArray(this.contributorsList.all.data)) {
        listToDisplay = this.contributorsList.all.data;
      } else if (this.showIncoming === 'Incoming' && this.contributorsList && this.contributorsList.incoming && Array.isArray(this.contributorsList.incoming.commitments)) {
        listToDisplay = this.contributorsList.incoming.commitments;
      } else if (this.showIncoming === 'Outgoing' && this.contributorsList && this.contributorsList.outgoing && Array.isArray(this.contributorsList.outgoing.commitments)) {
        listToDisplay = this.contributorsList.outgoing.commitments;
      }

      // Ensure that listToDisplay is an array before calling map
      if (!Array.isArray(listToDisplay)) {
        listToDisplay = [];
      }

      // Map the list to return formatted data
      return listToDisplay.map(contributor => ({
        id: contributor.id,
        user_id: contributor.user_id,
        name: contributor.name,
        type: contributor.type,
        date: this.formatDate(contributor.start_date),
        status: contributor.status,
        amount: `${contributor.amount} ${contributor.currency}`,
      }));
    },
  },
  methods: {
    backButton() {
      this.$inertia.visit('/contribution-detail');
    },
    handleSecondaryBtn() {
      this.$inertia.visit('/share-funds');
      this.modalStatus = true;
    },
    payNowClick(row) {
      const id = row.user_id;
      this.$store.commit('SET_FUND_TYPE', 'transfer');
      this.$store.commit('SET_SELECTED_USER_ID', id);
      this.$inertia.visit(`/share-funds`);
    },
    getUserList() {
      this.loading = true;
      this.$inertia.get('/friend-and-family', {}, {
        preserveState: true,
        onSuccess: (page) => {
          this.loading = false;
          this.contributorsList = page.props.contributorsList;
        }
      });
    },
    toggleData(isIncoming) {
      if(isIncoming.label === 'Incoming'){
        this.showIncoming = isIncoming.label;
      }else if(isIncoming.label === 'Outgoing'){
        this.showIncoming = isIncoming.label;
      }else if(isIncoming.label === 'All'){
        this.showIncoming = isIncoming.label;
      }



    },
    formatDate(dateString) {
      if (!dateString) {
        return '';
      }
      return dateString.split(' ')[0];
    },
    viewDetail(id){
      const contributorType = this.showIncoming === 'Incoming' ? 'incoming': 'outgoing';
      const contributionDetails = {
        id: id,
        type : contributorType,

      }
      this.$store.commit('SET_CONTRIBUTOR_DETAIL', contributionDetails);
      this.$inertia.visit('/contribution-individual-detail')
    },
    userUnFriend(user_id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to unfriend this user?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4682BE',
        cancelButtonColor: '#bcbcbc',
        confirmButtonText: 'Yes, unfriend!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;

          this.$inertia.post('removeConnectedUser', { user_id: user_id }, {
            preserveState: true,
            onSuccess: (page) => {
              this.loading = false;
              this.$inertia.visit('/friend-and-family');
            }
          });
        }
      });
    }


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
  width: 1440px;
  overflow-x: scroll;
}

.details-card {
  min-width: 222px;
}

/* Scroll bar stylings */
::-webkit-scrollbar {
  display: none;
}

.action-button {
  border-radius: 50%;
  border: 1px solid #4682be;
  width: 40px;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
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
  color:  #4682BE;;
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
